from picamera.array import PiRGBArray
from picamera import PiCamera
import time
import numpy as np
import cv2
import imutils

# initalize camera
camera = PiCamera()
camera.resolution = (640, 480)
camera.framerate = 24

# setup static exposure/shutter/white balance/gain settings
time.sleep(2)
camera.shutter_speed = camera.exposure_speed
camera.exposure_mode = 'off'
g = camera.awb_gains
camera.awb_mode = 'off'
camera.awb_gains = g

# capture video
rawCapture = PiRGBArray(camera, size=(640, 480))

# camera warmup
time.sleep(0.1)

# creating background subtraction
fgbg = cv2.createBackgroundSubtractorKNN()

# loop through each frame
for frame in camera.capture_continuous(rawCapture, format="bgr", use_video_port=True):
    # get NumPy array from the frame
    image = frame.array

    # blur image frame and apply background subtraction
    mask = cv2.GaussianBlur(image, (21, 21), 0)
    mask = fgbg.apply(mask)

    # apply a threshold to image frame
    ret, mask = cv2.threshold(mask,20,255,cv2.THRESH_BINARY)

    # erode image to remove outer noise,
    # then dilate to fill inner pixels of object in the frame
    mask = cv2.erode(mask, None, iterations=2)
	mask = cv2.dilate(mask, None, iterations=2)

    # get contours/edges of the objext
    mask,contours, h = cv2.findContours(mask,cv2.RETR_TREE,cv2.CHAIN_APPROX_SIMPLE)

    for m in contours:
        # filter objects that have an area less than specified
		if cv2.contourArea(m) < 6000:
			continue

        # get bounding rectangle of object
        (x, y, w, h) = cv2.boundingRect(m)

        # if object is bigger or smaller than a certian width or height just ignore
        if w > 300 and w < 90 and h > 300 and h < 190 and h > w*2.75:
			continue



    # for next frame, clear stream
    rawCapture.truncate(0)

    # break out of loop when 'q' is pressed
    if key == ord("q"):
        break

# clean up before exit
camera.release()
cv2.destroyAllWindows()
