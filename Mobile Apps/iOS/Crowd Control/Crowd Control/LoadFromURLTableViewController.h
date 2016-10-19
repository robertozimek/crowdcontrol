//
//  LoadFromURLTableViewController.h
//  Crowd Control
//
//  Created by Robert Ozimek on 10/18/16.
//  Copyright © 2016 Robert Ozimek. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AFNetworking/AFNetworking.h>
#import "CrowdControlAPIWrapper.h"

@interface LoadFromURLTableViewController : UITableViewController
    @property (nonatomic,strong) CrowdControlAPIWrapper *wrapper;

    - (void)retreiveFromAPI:(NSURL *) url;
    - (void)loadDataFromAPI:(NSArray *)companies;
@end
