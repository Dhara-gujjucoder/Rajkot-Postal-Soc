<------- Ledger Reports ------->
1) tarij report               --> created from double entry
2) Journel report             --> created from bulk entry
3) share ledger report        --> fom member and its relationship and from sharedetails
4) ledger fixed saving report --> member and its relationship and MemberFixedSaving (for only double entry)



Loan Module
1) Partial payment
    --> all pending emi will be deleted and partial payment amount is entered as EMI.
        a. if partial payment is same as pending loan than loan is mark as completed
        b. if still loan amount is remaining than that amount is divided into loan EMIs

2) Loan Close or Settlement
    --> loan settlement amount is entered across loan _master
    --> change status as 3 (settled)



trunk :
users           --> Done  php artisan db:seed SuperAdminSeeder
member          --> Done
ledger account  --> Done
fixed saving    --> Done
share & detail  --> Done
bulk entry      -->
loan master     -->
laon emi        -->
balance sheet   -->
double entry    -->
receipt         -->




