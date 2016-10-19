//
//  RoomsTableViewController.h
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AFNetworking/AFNetworking.h>

@interface RoomsTableViewController : UITableViewController

@property (nonatomic, strong) NSArray *rooms;
@property (nonatomic, strong) NSString *company;
@property (nonatomic, strong) NSString *address;
@property (nonatomic, strong) NSString *branchId;
@property (nonatomic, assign) BOOL open;


@end
