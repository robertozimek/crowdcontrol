//
//  CrowdControlAPIWrapper.m
//  Crowd Control
//
//  Created by Robert Ozimek on 10/18/16.
//  Copyright © 2016 Robert Ozimek. All rights reserved.
//

#import "CrowdControlAPIWrapper.h"

@interface CrowdControlAPIWrapper ()
    @property (nonatomic, strong) NSString *baseURL;
    @property (nonatomic, strong) NSString *companiesURL;
    @property (nonatomic, strong) NSString *branchURL;
    @property (nonatomic, strong) NSString *roomURL;
    @property (nonatomic, strong) NSString *roomCrowdnessURL;
    @property (nonatomic, strong) NSDictionary *responeObject;
    @property (nonatomic, strong) NSArray *companies;
    @property (nonatomic, strong) NSArray *branches;
    @property (nonatomic, strong) NSArray *rooms;
    @property (nonatomic, strong) NSDictionary *room;
@end

@implementation CrowdControlAPIWrapper

+ (id)sharedInstance {
    static CrowdControlAPIWrapper *sharedAPIWrappper = nil;
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        sharedAPIWrappper = [[self alloc] init];
    });
    return sharedAPIWrappper;
}

- (id)init {
    if (self = [super init]) {
        self.baseURL = @"https://crowdcontrol-adriantam18.rhcloud.com/api/v1/";
    }
    return self;
}


- (NSURL *) getCompaniesURL {
    self.companiesURL = [NSString stringWithFormat:@"%@companies", self.baseURL];
    return [NSURL URLWithString:self.companiesURL];
}

- (NSURL *) getBranchURL:(NSString*)company {
    self.branchURL = [NSString stringWithFormat:@"%@branches/?company=%@", self.baseURL, company];
    return [NSURL URLWithString:self.branchURL];
}

- (NSURL *) getRoomsURLFromBranch:(NSString *) branch {
    self.roomURL = [NSString stringWithFormat:@"%@rooms/?branch_id=%@", self.baseURL, branch];
    return [NSURL URLWithString:self.roomURL];
}

- (NSURL *) getRoomCrowdnessURLForRoom:(NSString *) room {
    self.roomCrowdnessURL = [NSString stringWithFormat:@"%@rooms/%@", self.baseURL, room];
    return [NSURL URLWithString:self.roomCrowdnessURL];
}




@end
