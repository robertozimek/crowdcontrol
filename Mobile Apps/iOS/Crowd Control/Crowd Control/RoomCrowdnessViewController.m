//
//  RoomCrowdnessViewController.m
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import "RoomCrowdnessViewController.h"

@interface RoomCrowdnessViewController ()

@property (weak, nonatomic) IBOutlet UILabel *companyLabel;
@property (weak, nonatomic) IBOutlet UILabel *addressLabel;
@property (weak, nonatomic) IBOutlet UILabel *roomLabel;
@property (weak, nonatomic) IBOutlet UILabel *lastUpdateLabel;
@property (weak, nonatomic) IBOutlet UILabel *crowdnessLabel;
@property (weak, nonatomic) IBOutlet UILabel *roomCapacityLabel;
@property (weak, nonatomic) IBOutlet UIProgressView *progressView;

@end

@implementation RoomCrowdnessViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    if (!self.open) {
        self.navigationItem.title = @"CLOSED!";
    }
    
    
    self.company = [self.company stringByRemovingPercentEncoding];
    self.address = [self.address stringByRemovingPercentEncoding];
    self.room = [self.room stringByRemovingPercentEncoding];
    
    if((int)[[UIDevice currentDevice] systemVersion] < 9) {
        self.address = [self.address stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
        self.room = [self.room stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
        self.company = [self.company stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    }
    
    self.wrapper = [[CrowdControlAPIWrapper alloc] init];
    [self retreiveFromAPI:[self.wrapper getRoomCrowdnessURLForRoom:self.roomId]];
}


// Refresh data from the API
- (IBAction)refreshButton:(id)sender {
    [self retreiveFromAPI:[self.wrapper getRoomCrowdnessURLForRoom:self.roomId]];
}

// Request data from the API
- (void)retreiveFromAPI:(NSURL *) url{
    NSURL *URL = url;
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        // Retrieve data
        [self loadDataFromAPI:responseObject];
    } failure:^(NSURLSessionTask *operation, NSError *error) {
        // Report any error to user with an alert
        NSLog(@"Error: %@", error);
        if ([[[error userInfo] objectForKey:AFNetworkingOperationFailingURLResponseErrorKey] statusCode] != 404) {
            UIAlertController *alertController = [UIAlertController
                                                  alertControllerWithTitle:@"Error"
                                                  message:@"Unable to contact server"
                                                  preferredStyle:UIAlertControllerStyleAlert];
            UIAlertAction *okAction = [UIAlertAction
                                       actionWithTitle:NSLocalizedString(@"OK", @"OK action")
                                       style:UIAlertActionStyleDefault
                                       handler:^(UIAlertAction *action)
                                       {
                                       }];
            [alertController addAction:okAction];
            [self presentViewController:alertController animated:YES completion:nil];
        }
    }];
}

- (void)loadDataFromAPI:(id)JSONObject {
    self.crowd = [JSONObject objectForKey:@"data"][0];
    self.companyLabel.text = self.company;
    self.addressLabel.text = self.address;
    self.roomLabel.text = self.crowd[@"room_number"];
    self.lastUpdateLabel.text = self.crowd[@"time"];
    
    // Check if business is open before reporting crowdness
    if (self.open) {
        self.crowdnessLabel.text = [NSString stringWithFormat:@"%@%%", self.crowd[@"crowd"]];
        
        float crowdNumber = [self.crowd[@"crowd"] floatValue] / 100;
        
        self.progressView.progress = crowdNumber;
        if (crowdNumber < 0.50) {
            self.progressView.tintColor =[UIColor colorWithRed:0.20 green:0.60 blue:0.86 alpha:1.0];
        } else if(crowdNumber >= 0.50 && crowdNumber < 0.8) {
            self.progressView.tintColor = [UIColor colorWithRed:0.95 green:0.77 blue:0.07 alpha:1.0];
        } else if (crowdNumber >= 0.80) {
            self.progressView.tintColor = [UIColor colorWithRed:0.75 green:0.22 blue:0.17 alpha:1.0];
        }
    } else {
        self.crowdnessLabel.text = @"CLOSED";
        self.progressView.progress = 1;
        self.progressView.tintColor = [UIColor colorWithRed:0.75 green:0.22 blue:0.17 alpha:1.0];
    }
}

@end
