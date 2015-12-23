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
    
    
    // Encode strings for URL
    NSCharacterSet *set = [NSCharacterSet URLQueryAllowedCharacterSet];
    self.company = [self.company stringByAddingPercentEncodingWithAllowedCharacters:set];
    self.address = [self.address stringByAddingPercentEncodingWithAllowedCharacters:set];
    self.room = [self.room stringByAddingPercentEncodingWithAllowedCharacters:set];
    [self requestDataFromAPI];
}


// Refresh data from the API
- (IBAction)refreshButton:(id)sender {
    [self requestDataFromAPI];
}

// Request data from the API
- (void)requestDataFromAPI {
    // Set up URL for API call
    NSString *urlString = [NSString stringWithFormat:@"https://crowdcontrol-adriantam18.rhcloud.com/requests.php/?data=crowd&comp=%@&branch=%@&room=%@",self.company, self.address, self.room];
    
    NSURL *URL = [NSURL URLWithString:urlString];
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        
        // Fill in the data for company
        self.crowd = [responseObject objectForKey:@"crowd"];
        self.companyLabel.text = self.crowd[@"company"];
        self.addressLabel.text = self.crowd[@"address"];
        self.roomLabel.text = self.crowd[@"room"];
        self.roomCapacityLabel.text = self.capacity;
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

@end
