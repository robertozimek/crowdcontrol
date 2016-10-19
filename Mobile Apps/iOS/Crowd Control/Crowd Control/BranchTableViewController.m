//
//  BranchTableViewController.m
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import "BranchTableViewController.h"
#import "RoomsTableViewController.h"

@interface BranchTableViewController ()

@end

@implementation BranchTableViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // Encode string for URL 
    NSCharacterSet *set = [NSCharacterSet URLQueryAllowedCharacterSet];
    self.company = [self.company stringByAddingPercentEncodingWithAllowedCharacters:set];
    [self requestDataFromAPI];
}

// Refresh data from the API
- (IBAction)refreshButton:(id)sender {
    [self requestDataFromAPI];
}

// Request data from the API
- (void)requestDataFromAPI {
    // Set up URL for API call
    NSString *urlString = [NSString stringWithFormat:@"https://crowdcontrol-adriantam18.rhcloud.com/api/v1/branches/?company=%@",self.company];
    
    NSURL *URL = [NSURL URLWithString:urlString];
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        // Retrieve data and reload the table 
        self.branches = [responseObject objectForKey:@"data"];
        [self.tableView reloadData];
        
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

//  Send company name and address to RoomsTableViewController
-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender{
    if([segue.identifier isEqualToString:@"toRooms"]){
        RoomsTableViewController *roomController = (RoomsTableViewController *)segue.destinationViewController;
        NSIndexPath *savedSelection = self.tableView.indexPathForSelectedRow;
        UITableViewCell *selectedCell = [self.tableView cellForRowAtIndexPath:savedSelection];
        for(int i = 0; i < [self.branches count]; i++) {
            if (self.branches[i][@"address"] == selectedCell.textLabel.text) {
                
                // Pass company name and address to next view
                roomController.branchId = self.branches[i][@"branch_id"];
                roomController.company = self.branches[i][@"company_name"];
                roomController.address = self.branches[i][@"address"];
                
                
                // Current time split into hours and minutes
                NSDateComponents *components = [[NSCalendar currentCalendar] components:NSCalendarUnitHour | NSCalendarUnitMinute | NSCalendarUnitSecond fromDate:[NSDate date]];
                NSInteger currHr = [components hour];
                NSInteger currtMin = [components minute];
                
                // Get opening and closing times
                NSString *openTime = self.branches[i][@"opening_hours"];
                NSString *closeTime = self.branches[i][@"closing_hours"];
                
                // Split opening and closing hours into hours and minutes
                int opHr = [[[openTime componentsSeparatedByString:@":"] objectAtIndex:0] intValue];
                int opMin = [[[openTime componentsSeparatedByString:@":"] objectAtIndex:1] intValue];
                int clHr = [[[closeTime componentsSeparatedByString:@":"] objectAtIndex:0] intValue];
                int clMin = [[[closeTime componentsSeparatedByString:@":"] objectAtIndex:1] intValue];
                
                
                // Form single value time
                int opTime = (opHr * 60) + opMin;
                int clTime = (clHr * 60) + clMin;
                
                long timeNow = (currHr * 60) + currtMin;
                
                // Determine if it is open and then pass it on to next view
                if(timeNow >= opTime && timeNow <= clTime) {
                    roomController.open = YES;
                } else {
                    roomController.open = NO;
                }
                
                
                
                
            }
        }
        
    }
}


#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [self.branches count];
}


- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    static NSString *CellIdentifier = @"Branch Cell";
    UITableViewCell *cell = [tableView
                             dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    
    
    cell.textLabel.text=[self.branches objectAtIndex:indexPath.row][@"address"];
    
    
    return cell;
}



@end
