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

