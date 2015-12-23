//
//  ViewController.m
//  Crowd Control
//
//  Created by Robert Ozimek on 12/17/15.
//  Copyright © 2015 Robert Ozimek. All rights reserved.
//

#import "CompaniesViewController.h"
#import "BranchTableViewController.h"

@interface CompaniesViewController ()

@end

@implementation CompaniesViewController

// Once view is loaded
- (void)viewDidLoad {
    [super viewDidLoad];
    [self requestDataFromAPI];
}

// Refresh data from the API
- (IBAction)refreshButton:(id)sender {
    [self requestDataFromAPI];
}

// Request data from the API
- (void)requestDataFromAPI {
    // Set up URL for API call
    NSURL *URL = [NSURL URLWithString:@"https://crowdcontrol-adriantam18.rhcloud.com/requests.php/?data=comp"];
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        // Retrieve data and reload table
        self.companies = [responseObject objectForKey:@"companies"];
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

#pragma mark - Table view data source

// Fills table cells with data - built in function
-(UITableViewCell *)tableView:(UITableView *)tableView
        cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier =@"Company Cell";
    UITableViewCell *cell = [tableView
                             dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    
    
    cell.textLabel.text=[self.companies objectAtIndex:indexPath.row][@"company_name"];
    
    
    return cell;
}

// Send company name to BranchTableViewController
-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender{
    if([segue.identifier isEqualToString:@"toBranch"]){
        BranchTableViewController *branchController = (BranchTableViewController *)segue.destinationViewController;
        NSIndexPath *savedSelection = self.tableView.indexPathForSelectedRow;
        UITableViewCell *selectedCell = [self.tableView cellForRowAtIndexPath:savedSelection];
        branchController.company = selectedCell.textLabel.text;
    }
}

// Set table view sections
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}

// Set number of cells in tableview
- (NSInteger)tableView:(UITableView *)tableView
 numberOfRowsInSection:(NSInteger)section
{
    return [self.companies count];
}

@end
