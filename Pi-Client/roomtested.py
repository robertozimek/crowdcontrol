# import the necessary packages
from collections import deque
from picamera.array import PiRGBArray
from picamera import PiCamera
import time
import numpy as np
import cv2
import imutils

buf = [None] * 10
pts = deque(buf)
counter = 0
# initialize the camera and grab a reference to the raw camera capture
camera = PiCamera()
#camera.led = False
camera.resolution = (640, 480)
camera.framerate = 30
#camera.exposure_mode = 'fixedfps'
#camera.image_effect = 'denoise'

time.sleep(2)
camera.shutter_speed = camera.exposure_speed
camera.exposure_mode = 'off'
g = camera.awb_gains
camera.awb_mode = 'off'
camera.awb_gains = g

rawCapture = PiRGBArray(camera, size=(640, 480))

# allow the camera to warmup
time.sleep(0.1)

#camera.capture(rawCapture, format="bgr")
#sampleImage = rawCapture.array


kernel = cv2.getStructuringElement(cv2.MORPH_ELLIPSE,(3,3))
#fgbg = cv2.createBackgroundSubtractorMOG()
fgbg = cv2.createBackgroundSubtractorKNN()

#cv2.line(image, (320,280), (20, 180), (0, 0, 255), 2)

# capture frames from the camera
for frame in camera.capture_continuous(rawCapture, format="bgr", use_video_port=True):
	# grab the raw NumPy array representing the image, then initialize the timestamp
	# and occupied/unoccupied text
	image = frame.array
	cv2.line(image, (290, 310), (460, 180), (255, 128, 128), 1)
	cv2.line(image, (290, 330), (460, 200), (0, 0, 255), 2)
	cv2.line(image, (290, 350), (460, 220), (255, 128, 128), 1)

	#mask1 = cv2.cvtColor(image,cv2.COLOR_BGR2GRAY)
	mask = cv2.GaussianBlur(image, (21, 21), 0)
	mask = fgbg.apply(mask)
	#mask = cv2.cvtColor(mask,cv2.COLOR_BGR2GRAY)
	ret, mask = cv2.threshold(mask,20,255,cv2.THRESH_BINARY)
	#mask = cv2.morphologyEx(mask, cv2.MORPH_OPEN, kernel)
	mask = cv2.erode(mask, None, iterations=2)
	mask = cv2.dilate(mask, None, iterations=2)
	mask,contours, h = cv2.findContours(mask,cv2.RETR_TREE,cv2.CHAIN_APPROX_SIMPLE)
	center = None

#	if len(contours) > 0:
#		c = max(contours, key=cv2.contourArea)
#		((x,y), radius) = cv2.minEnclosingCircle(c)
#		M = cv2.moments(c)
#		center = (int(M["m10"] / M["m00"]), int(M["m01"] / M["m00"]))
#		
#		pts.appendleft(center)


	firstCount = 0
	secondCount = 0
	
	aCount = 0		
	for m in contours:
		if cv2.contourArea(m) < 6000:
			continue

		(x, y, w, h) = cv2.boundingRect(m)
		if w > 300 and w < 90 and h > 300 and h < 190 and h > w*2.75:
			continue

		aCount += 1
		#print "x: ", (x + (w/2)), " y: ", (y + (h/2))
		
		center = (x + (w/2), y + (h/2))
		
		pts.appendleft(center)

		# print "width: ",  w
		# print "height: ", h
		#cv2.rectangle(image, (x,y), (x + w, y + h), (255, 0, 0), 2)
		firstCount += 50
		secondCount += 30
		if secondCount > 255 or firstCount > 255 :
			firstCount = 30
			secondCount = 50
		cv2.circle(image, center, 5, (firstCount,secondCount,secondCount-20), -1)
	
	dirY = ""		

	#print pts
	for i in np.arange(1, len(pts)):
		#print "loop in np.arrange"
		#if pts[i - 1] is None or pts[i] is None:
		#	continue	
		if pts[-10] is not None:
			dY = pts[-10][1] - pts[i][1]
			dirY = "test"

			#print pts
			if np.abs(dY) > 20:
				dirY = "Up" if np.sign(dY) == 1 else "Down"
	cv2.putText(image, dirY, (10, 30), cv2.FONT_HERSHEY_SIMPLEX,
		0.65, (0, 255, 0), 3)

	# show the frame
	# cv2.imshow("Frame", mask)
	cv2.imshow("OG", image)

	counter += 1
	key = cv2.waitKey(1) & 0xFF

	# clear the stream in preparation for the next frame
	rawCapture.truncate(0)

	# if the `q` key was pressed, break from the loop
	if key == ord("q"):
		break

camera.release()
cv2.destroyAllWindows()
