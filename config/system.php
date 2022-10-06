<?php 

return [
    'max_sub_members_of_a_member' => 10,

    'activity' => [
        'logger' => env('ACTIVITY_LOGGER', 'file'), // database|file|null,
        'table' => 'activity_logs'
    ],

    'sub_members' => ['Father', 'Mother', 'Spouse', 'Child', 'Brother', 'Sister'],

    'currency' => [
        'symbol' => '৳',
        'short' => "BDT",
        'name' => "Bangladeshi Taka",
        'rates' => [
            'USD' => 81 // 1 USD = 81 BDT
        ]
    ],

    'user_roles' => [
        [ 'key' => 'master', 'name' => 'Super Admin' ],
        [ 'key' => 'admin', 'name' => 'Admin' ],
        [ 'key' => 'doctor', 'name' => 'Doctor' ],
        [ 'key' => 'patient', 'name' => 'Patient' ],
    ],

    'charge_types' => ['booking', 'reappoint', 'report'],
    
    'payment' => [
        'tax' => 0,
        'to_doctor' => 80,
        'default' => 'online',
        'default_method' => 'aamarpay',
        'gateway_methods' => [
            'manual' => [
                'bkash' => "Pay via bKash",
                'rocket' => "Pay via Rocket",
            ],
            'online' => [
                'aamarpay' => "AamarPay Payment",
                'portwallet' => "PortWallet Payment",
                'paypal' => "Paypal Payment",
            ],
        ],

    ],
    
    'days' => [
        'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'
    ],

    'shortcodes' => [
        '[[APP_NAME]]', '[[APP_URL]]', '[[LOGIN_URL]]', 
        '[[USER_NAME]]', '[[USER_EMAIL]]', '[[USER_MOBILE]]',  
        '[[APPOINTMENT_ID]]', '[[APPOINTMENT_CODE]]', 
        '[[APPOINTMENT_TIME]]', '[[APPOINTMENT_DATE]]', '[[APPOINTMENT_DATETIME]]',
    ],
    'medicine_types' => [
        "Capsule", "Syrup", "Tablet", "Ointment", "Suppository", "IM-Injection", "Injection", "Cream", "Eye drop", "Eye ointment", "Sachet", "IV Infusion", "Drop", "Soap", "Inhalation (dry powder)", "Hand rub", "Nasal Spray", "Solution", "Spray", "Inhalation Capsule", "Nebuliser Solution", "Tab/Capsule", "Liquid", "Type", "Nasal Ointment"
    ],
    'patient_history' => ['Present History', 'Past History', 'Allergic History', 'Personal History', 'Socioeconomic History', 'Surgery History', 'Obs. History', 'Gyne History'],
    'investigations' => [
        "Blood for CBC With ESR", "Platelet Count", "PBF", "TCE", "BT, CT", "PT with INR", "Blood for C/S", "Urine R/M/E", "Urine C/S", "Urine for Pregnancy Test ", "Stool R/E ", "Stool C/S", "Stool for OBT", "USG Whole Abdomen", "USG of Pregnancy Profile", "USG Ut-Adnexa", "USG HBS-Pancreas", "USG of KUB", "Fasting Sugar", "2hrs. ABF/75g Glucose", "RBS", "S. Uric Acid", "S. Amylase", "S. SGOT", "S. Bilirubin", "S.Bicarbonate", "S.Total Protein", "HbA1c", "S. Albumin", "A:G Ratio", "S. Creatinine", "S. Urea", "S. Lipase", "S. SGPT", "S.Alk. Phosphatase", "S. Electrolytes", "S. Iron Profile", "S. Calcium", "Troponin I", "S. BNP", "C-Reactive Protein (CRP)", "CRP", "Lipid Profile ", "S. Cholesterol", "Dengue NS1 Antigen", "Triple Antigen", "Dengue Antibody IgM ", "Dengue Antibody IgG", "Adenosine Deaminase (ADA)", "Pleural / Ascitic Fluid Study", "X-Ray Chest P/A View", "X-Ray P.N.S (O.M. View)", "X-Ray Skull Lt lateral view", "X-Ray Skull Rt lateral view", "X-Ray Skull Both view", "X-Ray Cervical Spine Lt lateral view", "X-Ray Cervical Spine Rt lateral view ", "X-Ray Cervical Spine Both view", "X-Ray KUB Region", "X-Ray Lumbo-Sacral Spine B/V", "Chest X-Ray Lateral View", "X-Ray abdomen in erect posture A/P view including both domes of Diaphragm", "X-Ray shoulder joint Rt", "X-Ray shoulder joint Lt", "X-Ray hip joint Rt", "X-Ray hip joint Lt", "X-Ray femur Rt B/V", "X-Ray femur Lt B/V", "Widal Test", "ASO Titre", "HBsAG (Screening)", "HBSAG (Conf.)", "ANA", "Anti CCP Antibody", "Anti DS-DNA", "VDRL", "TPHA", "R.A Test", "Blood Grouping & RH. Factor", "I.C.T for Kala-azar", "I.C.T for Malaria", "I.C.T for HIV", "Anti-HCV (ICT)", "Sputum for AFB", "Sputum for R/E ", "Sputum for Malignant Cell", "FT3 ", "FT4 ", "TSH ", "S. Cortisol", "S. LH  ", "S. FSH ", "S. GH ", "S. ACTH", "S. Testosterone", "S. Estradiol", "MT", "ECG", "ETT", "MRI of Brain ", "MRI of Brain with screening of spine ", "MRI of Cervical Spine", "MRI of L/S Spine", "CT-Scan of Brain ", "CT-Scan of Chest", "CT-Scan of whole abdomen", "CT-Scan of HBS ", "EEG", "NCS / NCV", "EMG", "Echo-2D, M-mode", "Color Doppler-Echo", "Hb-Electrophoresis", "Endoscopy of Upper GIT", "Colonoscopy ", "Bronchoscopy", "Colposcopy", "S. Ferritin", "S. Copper", "Urinary Copper", "Urinary electrolytes", "Semen Analysis", "Bone marrow study" 
    ]
];