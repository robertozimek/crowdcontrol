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
    NSCharacterSet *set = [NSCharacterSet URLQueryAllowedCharacterSet];
    self.company = [self.company stringByAddingPercentEncodingWithAllowedCharacters:set];
    self.address = [self.address stringByAddingPercentEncodingWithAllowedCharacters:set];
    [self requestDataFromAPI];
}

- (IBAction)refreshButton:(id)sender {
    [self requestDataFromAPI];
}

- (void)requestDataFromAPI {
    
    
    
    NSString *urlString = [NSString stringWithFormat:@"https://crowdcontrol-adriantam18.rhcloud.com/requests.php/?data=room&comp=%@&branch=%@",self.company, self.address];
    
    NSURL *URL = [NSURL URLWithString:urlString];
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    manager.requestSerializer = [AFJSONRequestSerializer serializer];
    manager.responseSerializer.acceptableContentTypes = [NSSet setWithObjects:@"text/html", @"text/json", @"text/javascript", @"text/plain", nil];
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        NSLog(@"JSON: %@", responseObject);
        self.rooms = [responseObject objectForKey:@"rooms"];
        [self.tableView reloadData];
        
    } failure:^(NSURLSessionTask *operation, NSError *error) {
        NSLog(@"Error: %@", error);
    }];
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
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
    
    
    NSLog(@"Room: %@",[self.rooms objectAtIndex:indexPath.row][@"room"]);
    cell.textLabel.text= [self.rooms objectAtIndex:indexPath.row][@"room"];
    
    
    return cell;
}

-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender{
    if([segue.identifier isEqualToString:@"toARoom"]){
        RoomCrowdnessViewController *roomController = (RoomCrowdnessViewController *)segue.destinationViewController;
        NSIndexPath *savedSelection = self.tableView.indexPathForSelectedRow;
        UITableViewCell *selectedCell = [self.tableView cellForRowAtIndexPath:savedSelection];
        for(int i = 0; i < [self.rooms count]; i++) {
            if (self.rooms[i][@"room"] == selectedCell.textLabel.text) {
                roomController.company = self.rooms[i][@"company"];
                roomController.address = self.rooms[i][@"address"];
                roomController.capacity = self.rooms[i][@"max_capacity"];
                roomController.room = self.rooms[i][@"room"];
            }
        }
        
    }
}


@end
