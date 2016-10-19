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
    self.wrapper = [[CrowdControlAPIWrapper alloc] init];
    [self retreiveFromAPI:self.wrapper.getCompaniesURL];
}

// Refresh data from the API
- (IBAction)refreshButton:(id)sender {
    [self retreiveFromAPI:self.wrapper.getCompaniesURL];
}

// Request data from the API
- (void)loadDataFromAPI:(id)JSONObject {
    self.companies = [JSONObject objectForKey:@"data"];
    [self.tableView reloadData];
}

#pragma mark - Table view data source

// Fills table cells with data - built in function
-(UITableViewCell *)tableView:(UITableView *)tableView
        cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier = @"Company Cell";
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
