<------- Ledger Reports ------->
1) tarij report (rojmel)               --> created from double entry
2) Journel report             --> created from bulk entry
3) share ledger report        --> fom member and its relationship and from sharedetails
4) ledger fixed saving report --> member and its relationship and MemberFixedSaving (for only double entry)



Loan Module
1) Partial payment
    --> all pending emi will be deleted and partial payment amount is entered as EMI.
        a. if partial payment is same as pending loan than loan is mark as completed
        b. if still loan amount is remaining than that amount is divided into loan EMIs

2) Loan Close or Settlement
    --> loan settlement amount is entered across loan_master
    --> change status as 3 (settled)



after all "db trunk" process :
users           --> Done  php artisan db:seed SuperAdminSeeder
member          --> Done
ledger account  --> Done
fixed saving    --> Done
share & detail  --> Done
loan master     -->
laon emi        -->
<!-- bulk entry      fixed saving commented rakkhvano thase  -->
balance sheet   --> Done
bulk entry --> Done
double entry    -->
<!-- receipt         -->  -->


<------- 04/18/2024 -------->

1) Journel Report
	-->added Other Expanse
		- Member Fee
		- Member Share
		- Double Entry data
2) Tarij Report
	- Member Fee   -->   count added in RDC bank
	- Member Share -->   count added in RDC bank



tomorrow ==> 19/2024
1 bulk entry for imported
2 share purchase date
3 for loan import

today ===> 29/05/2024
1) resign         done
2) loan cancel
3) parcial paymanet of loan

1) https://allevents.in/rajkot/laravel-rajkot-meetup-june-2024/80002513422490 metup whatsup group join





table added :

1) members :
    ALTER TABLE `members` ADD `ledger_group_id` INT(11) NULL DEFAULT NULL AFTER `payment_type`, ADD `payment_type_status` VARCHAR(255) NULL DEFAULT NULL AFTER `ledger_group_id`;

2) member_resign :
    ALTER TABLE `member_resign` ADD `ledger_group_id` INT(11) NULL DEFAULT NULL AFTER `payment_type`, ADD `payment_type_status` VARCHAR(255) NULL DEFAULT NULL AFTER `ledger_group_id`;

3) loan_master :
    ALTER TABLE `loan_master` ADD `ledger_group_id` INT(11) NULL DEFAULT NULL AFTER `payment_type`, ADD `payment_type_status` VARCHAR(255) NULL DEFAULT NULL AFTER `ledger_group_id`;

4) loan_emis :
    ALTER TABLE `loan_emis` ADD `ledger_group_id` INT(11) NULL DEFAULT NULL AFTER `payment_type`, ADD `payment_type_status` VARCHAR(255) NULL DEFAULT NULL AFTER `ledger_group_id`;
















https://www.google.com/maps/place/SHASHWAT+WORLD+THE+COMMERCIAL+SPACE/@22.236206,70.8162963,15z/data=!4m6!3m5!1s0x3959b55cf04f7e1b:0x406c21c4c0c23a4e!8m2!3d22.236206!4d70.8162963!16s%2Fg%2F11p15rxqjh?hl=en-GB&entry=ttu
