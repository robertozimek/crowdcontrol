//
//  BranchTableViewController.h
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AFNetworking/AFNetworking.h>
#import "LoadFromURLTableViewController.h"

@interface BranchTableViewController : LoadFromURLTableViewController

@property (nonatomic, strong) NSArray *branches;
@property (nonatomic, strong) NSString *company;

@end
