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
    --> loan settlement amount is entered across loan_master
    --> change status as 3 (settled)



after all "db trunk" process :
users           --> Done  php artisan db:seed SuperAdminSeeder
member          --> Done
ledger account  --> Done
fixed saving    --> Done
share & detail  --> Done
<!-- bulk entry      fixed saving commented rakkhvano thase  -->
loan master     --> Done
laon emi        --> Done
balance sheet   --> Done
double entry    -->
<!-- receipt         -->  -->


































https://www.google.com/maps/place/SHASHWAT+WORLD+THE+COMMERCIAL+SPACE/@22.236206,70.8162963,15z/data=!4m6!3m5!1s0x3959b55cf04f7e1b:0x406c21c4c0c23a4e!8m2!3d22.236206!4d70.8162963!16s%2Fg%2F11p15rxqjh?hl=en-GB&entry=ttu