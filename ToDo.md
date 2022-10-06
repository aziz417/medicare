# Next Tasks

1. Appointment for sub member - Done
2. History correction - Ignore
3. Prescription :
    - Advice
    - Investigation 
    - Medicine
    - Dosing
    - Instructions 
    - Template/cloning

4. Payment gateway (production) - Ignore
5. Dr profile Badges 
6. Patient wallet
7. Packages and plans.




> Full Progress can be found here  
> https://trello.com/b/JAdWHLt5/medics-bd

Full Page Chat Window Dashboard: 
https://doccure-html.dreamguystech.com/template/chat-doctor.html

Prescription Design
https://doccure-html.dreamguystech.com/template/edit-prescription.html

www.medcrypter.com
User: asad
Pass: P@ssw0rd


## Updated DB
```sql
ALTER TABLE `appointments`
ADD `notified` tinyint(1) NOT NULL DEFAULT '0' AFTER `is_completed`;
ALTER TABLE `transactions`
ADD `currency` varchar(20) NOT NULL DEFAULT 'BDT' AFTER `amount`;
ALTER TABLE `prescriptions`
ADD `investigations` text COLLATE 'utf8mb4_unicode_ci' NULL AFTER `advice`;
ALTER TABLE `templates`
ADD `hidden` tinyint(1) NOT NULL COMMENT '0' AFTER `removable`;
// DONE
```

## Next Work
