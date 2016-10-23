//
//  CrowdControlAPIWrapper.h
//  Crowd Control
//
//  Created by Robert Ozimek on 10/18/16.
//  Copyright © 2016 Robert Ozimek. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <AFNetworking/AFNetworking.h>

@interface CrowdControlAPIWrapper : NSObject
    + (id)sharedInstance;
    - (id)init;
    - (NSURL *) getCompaniesURL;
    - (NSURL *) getBranchURL:(NSString*)company;
    - (NSURL *) getRoomsURLFromBranch:(NSString *) branch;
    - (NSURL *) getRoomCrowdnessURLForRoom:(NSString *) room;
@end
