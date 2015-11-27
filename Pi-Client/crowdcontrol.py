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
