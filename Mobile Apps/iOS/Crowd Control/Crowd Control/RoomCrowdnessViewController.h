//
//  RoomCrowdnessViewController.h
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AFNetworking/AFNetworking.h>

@interface RoomCrowdnessViewController : UIViewController

@property (nonatomic, strong) NSDictionary *crowd;
@property (nonatomic, strong) NSString *company;
@property (nonatomic, strong) NSString *address;
@property (nonatomic, strong) NSString *room;
@property (nonatomic, strong) NSString *roomId;
@property (nonatomic, strong) NSString *capacity;
@property (nonatomic, assign) BOOL open;

@end
