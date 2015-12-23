//
//  RoomsTableViewController.m
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import "RoomsTableViewController.h"
#import "RoomCrowdnessViewController.h"

@interface RoomsTableViewController ()

@end

@implementation RoomsTableViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // Encode strings for URL
    NSCharacterSet *set = [NSCharacterSet URLQueryAllowedCharacterSet];
    self.company = [self.company stringByAddingPercentEncodingWithAllowedCharacters:set];
    self.address = [self.address stringByAddingPercentEncodingWithAllowedCharacters:set];
    [self requestDataFromAPI];
}

// Refresh data from the API
- (IBAction)refreshButton:(id)sender {
    [self requestDataFromAPI];
}

// Request data from the API
- (void)requestDataFromAPI {
    // Set up URL for the API call
    NSString *urlString = [NSString stringWithFormat:@"https://crowdcontrol-adriantam18.rhcloud.com/requests.php/?data=room&comp=%@&branch=%@",self.company, self.address];
    
    NSURL *URL = [NSURL URLWithString:urlString];
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        
        // Retrieve data and reload data into the table
        self.rooms = [responseObject objectForKey:@"rooms"];
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

// Send company name, address, capacity, room, and open status to RoomCrowdnessViewController
-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender{
    if([segue.identifier isEqualToString:@"toARoom"]){
        RoomCrowdnessViewController *roomController = (RoomCrowdnessViewController *)segue.destinationViewController;
        NSIndexPath *savedSelection = self.tableView.indexPathForSelectedRow;
        UITableViewCell *selectedCell = [self.tableView cellForRowAtIndexPath:savedSelection];
        for(int i = 0; i < [self.rooms count]; i++) {
            if (self.rooms[i][@"room"] == selectedCell.textLabel.text) {
                // Pass data to next view
                roomController.company = self.rooms[i][@"company"];
                roomController.address = self.rooms[i][@"address"];
                roomController.capacity = self.rooms[i][@"max_capacity"];
                roomController.room = self.rooms[i][@"room"];
                roomController.open = self.open;
            }
        }
        
    }
}


#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [self.rooms count];
}


- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *CellIdentifier = @"Rooms Cell";
    UITableViewCell *cell = [tableView
                             dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    cell.textLabel.text= [self.rooms objectAtIndex:indexPath.row][@"room"];
    
    return cell;
}


@end
