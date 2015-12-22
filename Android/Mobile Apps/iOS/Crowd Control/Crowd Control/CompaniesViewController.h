//
//  ViewController.h
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AFNetworking/AFNetworking.h>

@interface CompaniesViewController : UIViewController

@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (nonatomic, strong) NSArray *companies;
@end

