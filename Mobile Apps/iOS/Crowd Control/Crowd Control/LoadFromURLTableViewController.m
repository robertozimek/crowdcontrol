//
//  LoadFromURLTableViewController.m
//  Crowd Control
//
//  Created by Robert Ozimek on 10/18/16.
//  Copyright © 2016 Robert Ozimek. All rights reserved.
//

#import "LoadFromURLTableViewController.h"

@interface LoadFromURLTableViewController ()
@end

@implementation LoadFromURLTableViewController
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
}
@end
