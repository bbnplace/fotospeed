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

