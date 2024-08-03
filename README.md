# States

It is important to define (hard code) Processes. And limit the number of processes/states that can be. And allow Administrator to define activities under the processes.

## Activities
When Administrator, defines Activities, the following data is important:
- Activity Name
- Activity Description
- Assign to Staff (Enables assignment of task to a team member)
- Start Date and Time
- Finish Date Time

## Testing Task Assignment
- To Test Order Management System, we need the following accounts:
1. Administrator Account
2. Receptionists Account
3. Graphic Designer Account
4. Cashier Account
5. Customer Account
6. Production Team Account

## Enabling Image Resizing 

To enable creation of thumbnail with new order, we are using 

#### Install GD Extension (If Not Already Installed)
On Ubuntu
```
sudo apt-get install php-gd
```
On CentOS/RHEL
```
sudo yum install php-gd
```
On macOS (Using Homebrew)
```
brew install gd
```

## Removal of File from Order

After careful consideration, we believe it should only be possible to remove file during upload.
Once the order has been uploaded, it should not be possible to remove any of the uploaded file anymore.

## Order Cancellation

Administrator determines the stage at which cancellation can be done for an item.
If the item has passed that process, it should not be possible for either the Customer, Receptionist or Admin to CANCEL the order.

1. Set the Order Status to Cancelled
2. Update reports that the order is cancelled
3. Add a condition to task generation function to check if an order has been cancelled before generating new tasks. If order has been cancelled, new tasks should not be generated.
4. Notification should be sent to all team members with uncompleted tasks that the order has been cancelled
5. Send Notification to customer that the order has been cancelled.


## Additional Message for Customer
You may want to have it as someone's task or part of the guidelines for a task or a dedicated task to have someone to download the order assets. Failure to do so may result in everybody consuming data to download assets.


