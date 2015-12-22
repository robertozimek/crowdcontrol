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
    NSCharacterSet *set = [NSCharacterSet URLQueryAllowedCharacterSet];
    self.company = [self.company stringByAddingPercentEncodingWithAllowedCharacters:set];
    [self requestDataFromAPI];
}

- (IBAction)refreshButton:(id)sender {
    [self requestDataFromAPI];
}

- (void)requestDataFromAPI {
    NSString *urlString = [NSString stringWithFormat:@"https://crowdcontrol-adriantam18.rhcloud.com/requests.php/?data=branch&comp=%@",self.company];
    
    NSURL *URL = [NSURL URLWithString:urlString];
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    manager.requestSerializer = [AFJSONRequestSerializer serializer];
    manager.responseSerializer.acceptableContentTypes = [NSSet setWithObjects:@"text/html", @"text/json", @"text/javascript", @"text/plain", nil];
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        NSLog(@"JSON: %@", responseObject);
        self.branches = [responseObject objectForKey:@"branches"];
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
    return [self.branches count];
}


- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    static NSString *CellIdentifier = @"Branch Cell";
    UITableViewCell *cell = [tableView
                             dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    
    
    cell.textLabel.text=[self.branches objectAtIndex:indexPath.row][@"address"];
    
    
    return cell;
}

#pragma mark - Navigation

-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender{
    if([segue.identifier isEqualToString:@"toRooms"]){
        RoomsTableViewController *roomController = (RoomsTableViewController *)segue.destinationViewController;
        NSIndexPath *savedSelection = self.tableView.indexPathForSelectedRow;
        UITableViewCell *selectedCell = [self.tableView cellForRowAtIndexPath:savedSelection];
        for(int i = 0; i < [self.branches count]; i++) {
            if (self.branches[i][@"address"] == selectedCell.textLabel.text) {
                roomController.company = self.branches[i][@"company_name"];
                roomController.address = self.branches[i][@"address"];
            }
        }
        
    }
}

@end
