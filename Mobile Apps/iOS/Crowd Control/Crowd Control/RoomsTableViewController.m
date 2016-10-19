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
    self.wrapper = [[CrowdControlAPIWrapper alloc] init];
    [self retreiveFromAPI:[self.wrapper getRoomsURLFromBranch:self.branchId]];
}

// Refresh data from the API
- (IBAction)refreshButton:(id)sender {
    [self retreiveFromAPI:[self.wrapper getRoomsURLFromBranch:self.branchId]];
}

// Request data from the API
- (void)loadDataFromAPI:(id)JSONObject {
    self.rooms = [JSONObject objectForKey:@"data"];
    [self.tableView reloadData];
}


// Send company name, address, capacity, room, and open status to RoomCrowdnessViewController
-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender{
    if([segue.identifier isEqualToString:@"toARoom"]){
        RoomCrowdnessViewController *roomController = (RoomCrowdnessViewController *)segue.destinationViewController;
        NSIndexPath *savedSelection = self.tableView.indexPathForSelectedRow;
        UITableViewCell *selectedCell = [self.tableView cellForRowAtIndexPath:savedSelection];
        for(int i = 0; i < [self.rooms count]; i++) {
            if (self.rooms[i][@"room_number"] == selectedCell.textLabel.text) {
                // Pass data to next view
                roomController.company = self.company;
                roomController.address = self.address;
                roomController.room = self.rooms[i][@"room_number"];
                roomController.roomId = self.rooms[i][@"room_id"];
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
    
    cell.textLabel.text= [self.rooms objectAtIndex:indexPath.row][@"room_number"];
    
    return cell;
}


@end
