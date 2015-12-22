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

- (void)viewDidLoad {
    [super viewDidLoad];
    [self requestDataFromAPI];
}

- (IBAction)refreshButton:(id)sender {
    [self requestDataFromAPI];
}

- (void)requestDataFromAPI {
    NSURL *URL = [NSURL URLWithString:@"https://crowdcontrol-adriantam18.rhcloud.com/requests.php/?data=comp"];
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    manager.requestSerializer = [AFJSONRequestSerializer serializer];
    manager.responseSerializer.acceptableContentTypes = [NSSet setWithObjects:@"text/html", @"text/json", @"text/javascript", @"text/plain", nil];
    [manager GET:URL.absoluteString parameters:nil progress:nil success:^(NSURLSessionTask *task, id responseObject) {
        NSLog(@"JSON: %@", responseObject);
        self.companies = [responseObject objectForKey:@"companies"];
        [self.tableView reloadData];
        
    } failure:^(NSURLSessionTask *operation, NSError *error) {
        NSLog(@"Error: %@", error);
    }];
}

-(UITableViewCell *)tableView:(UITableView *)tableView
        cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier =@"Company Cell";
    UITableViewCell *cell = [tableView
                             dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    
    
    cell.textLabel.text=[self.companies objectAtIndex:indexPath.row][@"company_name"];
    
    
    return cell;
}

-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender{
    if([segue.identifier isEqualToString:@"toBranch"]){
        BranchTableViewController *branchController = (BranchTableViewController *)segue.destinationViewController;
        NSIndexPath *savedSelection = self.tableView.indexPathForSelectedRow;
        UITableViewCell *selectedCell = [self.tableView cellForRowAtIndexPath:savedSelection];
        branchController.company = selectedCell.textLabel.text;
    }
}


- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView
 numberOfRowsInSection:(NSInteger)section
{
    return [self.companies count];
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
