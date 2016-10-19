from picamera.array import PiRGBArray
from picamera import PiCamera
import time
import numpy as np
import argparse
import cv2
import imutils
import json
import requests

# construct the argument parser and parse the arguments
ap = argparse.ArgumentParser()
ap.add_argument("-c", "--conf", required=True,
                help="path to the JSON configuration file")
args = vars(ap.parse_args())
conf = json.load(open(args["conf"]))


def camera_setup(camera):
    # camera setup
    camera.resolution = (320, 240)
    camera.framerate = 16

    # set static camera settings
    time.sleep(2)
    camera.shutter_speed = camera.exposure_speed
    camera.exposure_mode = 'off'
    g = camera.awb_gains
    camera.awb_mode = 'off'
    camera.awb_gains = g


def add_zone_lines(image):
    # draw zone lines on video feed
    zones = conf["zone_lines"]
    for line in zones:
        cv2.line(image,
                 (zones["point1"][0], zones["point1"][1]),
                 (zones["point1"][0], zones["point1"][1]),
                 (zones["color"][0], zones["color"][1], zones["color"][2]),
                 zones["linetype"])

# post request information
url = conf["url"]
data = {'id': conf["room_id"], 'in': '0', 'out': '0', 'auth': conf["key"]}
headers = {'Content-type': 'application/json'}

# initalize people counters
totalCount = 0
inCount = 0
outCount = 0

camera = PiCamera()
camera_setup(camera)
rawCapture = PiRGBArray(camera, size=(320, 240))
# allow the camera to warmup
time.sleep(0.1)

# set up background subtraction
bgSub = cv2.createBackgroundSubtractorKNN()

# initalize booleans for counting zones
zone1 = False
zone2 = False
zone3 = False
resetIn = False
resetOut = False
timerCount = 0

# capture frames from the camera
for frame in camera.capture_continuous(rawCapture, format="bgr", use_video_port=True):
    # grab frame of video feed
    image = frame.array

    add_zone_lines(image)

    # apply a blur and background subtraction
    mask = cv2.GaussianBlur(image, (21, 21), 0)
    mask = bgSub.apply(mask)

    # apply threshold
    ret, mask = cv2.threshold(mask, 20, 255, cv2.THRESH_BINARY)

    # erode and dilate to remove noise than fill in pixel in object
    mask = cv2.erode(mask, None, iterations=2)
    mask = cv2.dilate(mask, None, iterations=2)

    # find the contour/blobs
    mask, contours, h = cv2.findContours(
        mask, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)

    aCount = 0

    # loop through objects found
    for m in contours:
        # ignore noise
        if timerCount <= 4:
            timerCount += 1
            continue

        if aCount >= 1:
            continue

        # ignore object that are too small
        if cv2.contourArea(m) < 2000:
            continue

        # get size and point of object
        (x, y, w, h) = cv2.boundingRect(m)

        # ignore objects that don't fit criteria of a person
        if w > 140 and w < 70 and h > 140 and h < 70 and h > w * 2.75:
            continue

        aCount += 1

        # calculate the center of object
        center = (x + (w / 2), y + (h / 2))

        # ignore shadow on the door
        if center[0] < 117:
            continue

        # trigger zones as object moves through them
        if center[1] > 75 and center[1] < 115:
            resetOut = True
        if center[1] >= 115 and center[1] < 125:
            zone1 = True
        if center[1] >= 125 and center[1] < 143:
            zone2 = True
        if center[1] >= 145 and center[1] < 155:
            zone3 = True
        if center[1] >= 155 and center[1] < 195:
            resetIn = True

        # determine if person left and count them
        if (resetOut) and (zone1 or zone2 or zone3):
            totalCount += 1
            outCount += 1
            zone1 = False
            zone2 = False
            zone3 = False
            resetOut = False
            resetIn = False
            # update count on server
            data = {'id': conf["room_id"], 'in': '0',
                    'out': '1', 'auth': conf["key"]}
            r = requests.post(url, data=json.dumps(data), headers=headers)

        # determine if person entered and count them
        elif (resetIn) and (zone1 or zone2 or zone3):
            totalCount += 1
            inCount += 1
            zone1 = False
            zone2 = False
            zone3 = False
            resetOut = False
            resetIn = False
            # update count on server
            data = {'id': conf["room_id"], 'in': '1',
                    'out': '0', 'auth': conf["key"]}
            r = requests.post(url, data=json.dumps(data), headers=headers)
        else:
            resetOut = False
            resetIn = False

        # Display the number of object on video feed
        cv2.putText(image, "{}".format(aCount), center,
                    cv2.FONT_HERSHEY_SIMPLEX, 0.65, (0, 255, 0), 2)

    # Display people count on video feed
    cv2.putText(image, "total: {}".format(totalCount), (10, 30),
                cv2.FONT_HERSHEY_SIMPLEX, 0.65, (0, 255, 0), 2)
    cv2.putText(image, "In: {}".format(inCount), (10, 60),
                cv2.FONT_HERSHEY_SIMPLEX, 0.65, (0, 255, 0), 2)
    cv2.putText(image, "Out: {}".format(outCount), (10, 90),
                cv2.FONT_HERSHEY_SIMPLEX, 0.65, (0, 255, 0), 2)

    # Show window with video feed
    cv2.imshow("OG", image)

    # capture keyboard press
    key = cv2.waitKey(1) & 0xFF

    # clear the stream in preparation for the next frame
    rawCapture.truncate(0)

    # if the `q` key was pressed, break from the loop
    if key == ord("q"):
        break

# cleanup
camera.release()
cv2.destroyAllWindows()
