<?php

/*
|--------------------------------------------------------------------------
| Team / "Meet Our IT Experts" content
|--------------------------------------------------------------------------
|
| DEMO CONTENT. Every profile below is fictional and exists to demonstrate
| the team section layout. Names, biographies, experience figures and
| certifications are placeholders, not verified credentials of real people.
|
| To publish real staff:
|   1. Set 'is_demo' => false.
|   2. Replace each entry in 'members' with real details.
|   3. Set 'photo' to a real image path (e.g. '/images/team/jane-doe.jpg').
|      When 'photo' is null the card falls back to a generated monogram
|      avatar, so no real person's likeness is ever implied.
|   4. Set 'certifications_verified' => true only for credentials you have
|      actually sighted and confirmed.
|
*/

return [

    // Master switch for the "sample profiles" notice shown on the page.
    'is_demo' => true,

    'section' => [
        'eyebrow' => 'SYSTEM://TEAM',
        'title' => 'Meet our IT experts.',
        'description' => 'A support bench spanning twelve disciplines: endpoint support, network infrastructure, server and identity administration, security operations, cloud productivity, service desk leadership, DevOps automation, AI engineering, data and BI, asset management, compliance training, and web hosting operations.',
        'demo_notice' => 'These are fictional sample profiles created to demonstrate this section. They are not real employees, and the experience figures and certifications shown are placeholders pending verification.',
    ],

    // Practice areas used for the filter chips. Order controls display order.
    'practices' => [
        'support' => 'Endpoint Support',
        'infrastructure' => 'Infrastructure',
        'systems' => 'Systems & Identity',
        'security' => 'Security',
        'cloud' => 'Cloud & M365',
        'leadership' => 'Service Desk',
        'devops' => 'DevOps & Automation',
        'ai' => 'AI Engineering',
        'data' => 'Data & BI',
        'assets' => 'Asset Management',
        'training' => 'Training & Consulting',
        'webops' => 'Web & Hosting',
    ],

    'members' => [

        [
            'slug' => 'marcus-whitfield',
            'name' => 'Marcus Whitfield',
            'certifications_verified' => false,
            'title' => 'Senior IT Support Engineer',
            'practice' => 'support',
            'years' => 13,
            'location' => 'Tier-2 Escalations',
            'tagline' => 'If it boots, breaks, or blue-screens, it lands on my bench.',
            'initials' => 'MW',
            'photo' => null,
            'bio' => 'Marcus has spent thirteen years doing the unglamorous work that keeps offices running: dead laptops, corrupted driver stacks, printers that only fail on deadline day. He came up through a retail repair bench and a 400-seat managed services desk before moving into senior escalation, where he now handles the tickets nobody else could close. He is known for writing the fix down afterwards so the same ticket never comes back twice.',
            'skills' => [
                'Windows 10/11', 'macOS', 'Hardware Diagnostics', 'Active Directory',
                'Intune / MDM', 'BitLocker', 'Dell & Lenovo Warranty', 'Imaging & Deployment',
                'Office 365 Apps', 'VPN Client Support',
            ],
            'certifications' => [
                'CompTIA A+',
                'CompTIA Network+',
                'Microsoft 365 Certified: Modern Desktop Administrator Associate (MD-102)',
                'Dell Certified Technician',
                'Apple Certified Support Professional (ACSP)',
            ],
            'responsibilities' => [
                'Tier-2 and Tier-3 desktop and laptop escalation for Windows and macOS fleets',
                'Hardware fault isolation, warranty coordination, and repair-or-replace decisions',
                'OS imaging, driver baseline management, and standard build maintenance',
                'Software packaging, licence compliance checks, and controlled application rollout',
                'Root-cause write-ups and knowledge base articles for recurring fault patterns',
            ],
        ],

        [
            'slug' => 'priya-raghunathan',
            'name' => 'Priya Raghunathan',
            'certifications_verified' => false,
            'title' => 'IT Infrastructure & Network Specialist',
            'practice' => 'infrastructure',
            'years' => 15,
            'location' => 'Network Operations',
            'tagline' => 'Fifteen years of chasing the packet that should not be there.',
            'initials' => 'PR',
            'photo' => null,
            'bio' => 'Priya has designed and repaired networks for logistics warehouses, multi-site clinics, and a manufacturing plant where a dropped link stopped a production line. She works from packet captures rather than guesswork, and she has a particular dislike of Wi-Fi that "mostly works". Fifteen years in, she still starts every investigation at layer one.',
            'skills' => [
                'Cisco IOS', 'VLANs & Trunking', 'BGP / OSPF', 'Site-to-Site VPN',
                'Fortinet FortiGate', 'pfSense', 'Wi-Fi Survey & Heatmapping', '802.1X / RADIUS',
                'PRTG / Zabbix Monitoring', 'Structured Cabling', 'SD-WAN',
            ],
            'certifications' => [
                'Cisco Certified Network Associate (CCNA)',
                'Cisco Certified Network Professional (CCNP Enterprise)',
                'Fortinet NSE 4',
                'CompTIA Network+',
                'CWNP Certified Wireless Network Administrator (CWNA)',
            ],
            'responsibilities' => [
                'Router, switch, and wireless controller configuration across client sites',
                'Firewall policy design, rule review, and change control',
                'VPN provisioning for remote staff and site-to-site links',
                'Network monitoring, alert tuning, and capacity planning',
                'Wi-Fi site surveys, channel planning, and interference remediation',
                'ISP liaison, circuit provisioning, and failover testing',
            ],
        ],

        [
            'slug' => 'daniel-okonkwo',
            'name' => 'Daniel Okonkwo',
            'certifications_verified' => false,
            'title' => 'Systems Administrator',
            'practice' => 'systems',
            'years' => 11,
            'location' => 'Server & Identity',
            'tagline' => 'Group Policy is documentation that actually enforces itself.',
            'initials' => 'DO',
            'photo' => null,
            'bio' => 'Daniel runs the servers and the directory underneath them. Eleven years of Windows Server, Active Directory, and Microsoft 365 tenancy work has made him deeply suspicious of anything configured by hand more than once, so he automates it. He has migrated three organisations off on-premise Exchange and restored two of them from backup after ransomware, which is why he tests restores on a schedule instead of trusting the backup log.',
            'skills' => [
                'Windows Server 2016-2022', 'Active Directory DS', 'Group Policy', 'DNS / DHCP',
                'Hyper-V', 'VMware vSphere', 'Microsoft 365 Admin Center', 'Entra ID Connect',
                'PowerShell Automation', 'Veeam Backup & Replication', 'File Server & NTFS Permissions',
            ],
            'certifications' => [
                'Microsoft Certified: Windows Server Hybrid Administrator Associate (AZ-800/801)',
                'Microsoft 365 Certified: Enterprise Administrator Expert (MS-102)',
                'VMware Certified Professional - Data Center Virtualization (VCP-DCV)',
                'CompTIA Server+',
                'ITIL 4 Foundation',
            ],
            'responsibilities' => [
                'Windows Server build, patching, and lifecycle management',
                'Active Directory design: OUs, group policy, delegation, and replication health',
                'User account provisioning, joiner-mover-leaver workflows, and access reviews',
                'Microsoft 365 tenancy administration and licence assignment',
                'Backup scheduling, restore testing, and disaster recovery runbooks',
                'Hypervisor and storage maintenance, snapshot hygiene, and capacity reporting',
            ],
        ],

        [
            'slug' => 'elena-vasquez',
            'name' => 'Elena Vasquez',
            'certifications_verified' => false,
            'title' => 'Cybersecurity & IT Security Specialist',
            'practice' => 'security',
            'years' => 14,
            'location' => 'Security Operations',
            'tagline' => 'Most breaches arrive as a polite email. I read them for a living.',
            'initials' => 'EV',
            'photo' => null,
            'bio' => 'Elena spent her first six years on a security operations floor triaging alerts at three in the morning, which taught her that detection is only half the job and containment is the other half. She now runs endpoint hardening, vulnerability programmes, and phishing simulation for client environments. She is blunt about risk: if a control cannot be evidenced, she does not count it as a control.',
            'skills' => [
                'Microsoft Defender for Endpoint', 'CrowdStrike Falcon', 'EDR / XDR Triage',
                'Tenable Nessus', 'Vulnerability Management', 'Conditional Access',
                'MFA & Identity Hardening', 'SIEM (Sentinel / Splunk)', 'Incident Response',
                'Phishing Simulation', 'ISO 27001 Alignment', 'Endpoint Encryption',
            ],
            'certifications' => [
                'CompTIA Security+',
                'Certified Information Systems Security Professional (CISSP)',
                'GIAC Certified Incident Handler (GCIH)',
                'Microsoft Certified: Security Operations Analyst Associate (SC-200)',
                'Certified Ethical Hacker (CEH)',
            ],
            'responsibilities' => [
                'Endpoint protection deployment, policy tuning, and alert triage',
                'Security monitoring, log correlation, and escalation of confirmed incidents',
                'Vulnerability scanning, prioritisation, and remediation tracking with owners',
                'Access control review, MFA enforcement, and privileged access governance',
                'Security awareness training and recurring phishing simulation campaigns',
                'Incident response: containment, eradication, recovery, and post-incident report',
            ],
        ],

        [
            'slug' => 'tomas-lindqvist',
            'name' => 'Tomas Lindqvist',
            'certifications_verified' => false,
            'title' => 'Cloud & Microsoft 365 Support Engineer',
            'practice' => 'cloud',
            'years' => 10,
            'location' => 'Cloud Services',
            'tagline' => 'Mail migrations are quiet until they are not. I plan for the not.',
            'initials' => 'TL',
            'photo' => null,
            'bio' => 'Tomas moved from on-premise Exchange administration into cloud engineering as tenants began to outnumber servers. Ten years in, he has run mailbox migrations for organisations between 40 and 4,000 seats, and he treats a cutover plan as a document that should survive contact with reality. He spends most of his week in Exchange Online, Entra ID, and Azure, usually on the problem that started as "email is slow".',
            'skills' => [
                'Exchange Online', 'Microsoft 365 Tenant Administration', 'Azure AD / Entra ID',
                'Azure IaaS & VMs', 'Microsoft Teams Administration', 'SharePoint Online',
                'Hybrid Exchange Migration', 'Mail Flow & Transport Rules', 'Intune Device Enrolment',
                'Azure Backup', 'PowerShell for M365', 'Conditional Access Policies',
            ],
            'certifications' => [
                'Microsoft 365 Certified: Messaging Administrator Associate (MS-203)',
                'Microsoft Certified: Azure Administrator Associate (AZ-104)',
                'Microsoft 365 Certified: Teams Administrator Associate (MS-700)',
                'AWS Certified Solutions Architect - Associate',
                'Microsoft Certified: Identity and Access Administrator Associate (SC-300)',
            ],
            'responsibilities' => [
                'Microsoft 365 tenant setup, licensing, and service health monitoring',
                'Email migration from on-premise Exchange, Google Workspace, and legacy IMAP',
                'Exchange Online mail flow, transport rules, and deliverability troubleshooting',
                'Azure virtual machine provisioning, networking, and backup configuration',
                'Teams, SharePoint, and OneDrive deployment with permission modelling',
                'Cloud identity configuration: Entra ID Connect, SSO, and conditional access',
            ],
        ],

        [
            'slug' => 'aisha-rahman',
            'name' => 'Aisha Rahman',
            'certifications_verified' => false,
            'title' => 'IT Help Desk & Technical Support Manager',
            'practice' => 'leadership',
            'years' => 12,
            'location' => 'Service Desk',
            'tagline' => 'A ticket queue is a promise. I make sure we keep it.',
            'initials' => 'AR',
            'photo' => null,
            'bio' => 'Aisha has run service desks since she was a team lead of four, and she has never lost the habit of picking up a call herself when the queue spikes. Twelve years of ITIL-aligned operations have given her a clear view of where support breaks: unclear ownership, untuned priorities, and SLAs nobody measures. She fixes those first, then the tooling. Her teams tend to stay, which she considers the real metric.',
            'skills' => [
                'ITIL 4 Service Management', 'Freshservice / Zendesk', 'Jira Service Management',
                'SLA Design & Reporting', 'Ticket Triage & Prioritisation', 'Escalation Management',
                'Remote Support Tooling', 'Knowledge Management', 'CSAT & QA Programmes',
                'Team Scheduling & Coaching', 'Major Incident Coordination',
            ],
            'certifications' => [
                'ITIL 4 Managing Professional',
                'HDI Support Center Manager',
                'CompTIA Project+',
                'Microsoft Certified: Dynamics 365 Fundamentals',
                'Lean Six Sigma Yellow Belt',
            ],
            'responsibilities' => [
                'Day-to-day operation of the IT service desk and ticket queue',
                'SLA definition, monitoring, and monthly performance reporting',
                'Ticket triage, priority assignment, and escalation path ownership',
                'Remote support delivery standards and first-contact-resolution tracking',
                'Major incident coordination and stakeholder communication',
                'Customer satisfaction measurement, QA call review, and analyst coaching',
            ],
        ],

        [
            'slug' => 'nadia-farouk',
            'name' => 'Nadia Farouk',
            'certifications_verified' => false,
            'title' => 'DevOps & IT Automation Engineer',
            'practice' => 'devops',
            'years' => 11,
            'location' => 'Automation & Delivery',
            'tagline' => 'If a task has been done by hand three times, that is a script I owe you.',
            'initials' => 'NF',
            'photo' => null,
            'bio' => 'Nadia started as a Linux administrator for a hosting provider, where a bad deploy at 2am was a personal matter. Eleven years later she builds the pipelines and automation that stop those nights happening: infrastructure as code, repeatable server builds, and PowerShell or Python scripts that replace the spreadsheet-and-manual-steps workflow most IT teams are still living with. She documents everything she automates, because an undocumented script is just a future outage with better branding.',
            'skills' => [
                'PowerShell Automation', 'Python IT Automation', 'Bash Scripting',
                'CI/CD Pipelines', 'Docker', 'Kubernetes', 'Terraform / IaC',
                'Git & Source Control', 'Linux Administration', 'Nginx Reverse Proxy',
                'Ansible', 'Log Analysis & Diagnostics',
            ],
            'certifications' => [
                'Red Hat Certified System Administrator (RHCSA)',
                'Certified Kubernetes Administrator (CKA)',
                'HashiCorp Certified: Terraform Associate',
                'AWS Certified SysOps Administrator - Associate',
                'Microsoft Certified: Azure Administrator Associate (AZ-104)',
            ],
            'responsibilities' => [
                'PowerShell and Python automation for recurring IT and reporting tasks',
                'CI/CD pipeline design, build, and maintenance for client applications',
                'Infrastructure as code for repeatable server and environment provisioning',
                'Container build, registry management, and Kubernetes deployment support',
                'Linux server administration, reverse proxy configuration, and hardening',
                'Script review, testing, and secure handling of secrets and configuration',
            ],
        ],

        [
            'slug' => 'kwame-asante',
            'name' => 'Kwame Asante',
            'certifications_verified' => false,
            'title' => 'AI Solutions & LLM Integration Engineer',
            'practice' => 'ai',
            'years' => 10,
            'location' => 'AI Engineering',
            'tagline' => 'An AI agent is only as good as the permissions you refuse to give it.',
            'initials' => 'KA',
            'photo' => null,
            'bio' => 'Kwame has spent ten years moving from backend integration work into applied AI engineering, and he is most useful where those two meet: getting a model to actually reach the systems it needs without handing it the keys to everything. He builds retrieval pipelines, helpdesk and support agents, and MCP integrations, then spends the unglamorous time on evaluation and guardrails. He treats prompt injection as a live threat rather than a conference topic, which is why his agents run with scoped permissions and logged tool calls.',
            'skills' => [
                'LLM API Integration', 'RAG Pipelines', 'Vector Search & Embeddings',
                'MCP Server Development', 'AI Agent Architecture', 'Prompt Injection Defense',
                'Python', 'Node.js', 'AI Agent Evaluation', 'Local Model Deployment',
                'Cost & Latency Optimization', 'Workflow Automation',
            ],
            'certifications' => [
                'AWS Certified Machine Learning - Specialty',
                'Microsoft Certified: Azure AI Engineer Associate (AI-102)',
                'Google Cloud Professional Machine Learning Engineer',
                'TensorFlow Developer Certificate',
                'CompTIA Security+',
            ],
            'responsibilities' => [
                'AI agent design, development, and deployment for support and operations use cases',
                'Retrieval-augmented generation over client knowledge bases and document stores',
                'MCP server configuration and custom tool integration for agent workflows',
                'Agent evaluation, observability, and regression testing before release',
                'Prompt injection defence, tool permission scoping, and output guardrails',
                'Local model deployment and cost, latency, and reliability tuning',
            ],
        ],

        [
            'slug' => 'sofia-marchetti',
            'name' => 'Sofia Marchetti',
            'certifications_verified' => false,
            'title' => 'Data & Business Intelligence Specialist',
            'practice' => 'data',
            'years' => 12,
            'location' => 'Data & Reporting',
            'tagline' => 'Every dashboard is an argument. I make sure the numbers can take the cross-examination.',
            'initials' => 'SM',
            'photo' => null,
            'bio' => 'Sofia has spent twelve years turning operational mess into reporting that people actually trust. She has built warehouses for retail chains, cleaned customer databases that had three versions of the same company, and rebuilt Power BI models where the totals never quite reconciled. Her rule is simple: a number is not finished until you can trace it back to its source row. She is equally happy writing SQL at 9am and explaining a margin variance to a board at 4pm.',
            'skills' => [
                'Power BI', 'Tableau', 'SQL Server / T-SQL', 'SQL Query Optimization',
                'Data Warehouse Design', 'ETL Pipelines', 'Data Cleansing',
                'Excel & Spreadsheet Automation', 'Python (pandas)', 'Data Modelling',
                'Automated Reporting', 'KPI Definition',
            ],
            'certifications' => [
                'Microsoft Certified: Power BI Data Analyst Associate (PL-300)',
                'Microsoft Certified: Azure Data Engineer Associate (DP-203)',
                'Tableau Certified Data Analyst',
                'Google Data Analytics Professional Certificate',
                'CompTIA Data+',
            ],
            'responsibilities' => [
                'Business intelligence dashboard design and delivery in Power BI and Tableau',
                'Data warehouse modelling, star schemas, and ETL pipeline construction',
                'SQL query tuning and database performance investigation',
                'Data cleansing, de-duplication, and migration between systems',
                'Automated reporting and scheduled distribution to stakeholders',
                'KPI definition, metric governance, and reconciliation of conflicting figures',
            ],
        ],

        [
            'slug' => 'hassan-qureshi',
            'name' => 'Hassan Qureshi',
            'certifications_verified' => false,
            'title' => 'IT Asset & Endpoint Lifecycle Manager',
            'practice' => 'assets',
            'years' => 13,
            'location' => 'Asset & Endpoint',
            'tagline' => 'You cannot secure a laptop you did not know you owned.',
            'initials' => 'HQ',
            'photo' => null,
            'bio' => 'Hassan has spent thirteen years on the part of IT that nobody notices until an audit: knowing exactly what hardware exists, where it is, who has it, and what it is running. He has run asset programmes through office moves, acquisitions, and a hardware refresh across nine sites, and he has cleaned up licence positions that were quietly non-compliant for years. His inventories are the kind that survive a software audit without a scramble.',
            'skills' => [
                'IT Asset Inventory', 'CMDB & Asset Registers', 'Software Licence Tracking',
                'Microsoft Intune', 'SCCM / MECM', 'Endpoint Configuration',
                'Device Lifecycle Planning', 'Procurement & Warranty Management',
                'Patch Compliance Reporting', 'Asset Tagging & Barcoding',
                'CCTV & Physical IT Security', 'Audit Preparation',
            ],
            'certifications' => [
                'Certified IT Asset Manager (CITAM)',
                'Microsoft 365 Certified: Endpoint Administrator Associate (MD-102)',
                'ITIL 4 Foundation',
                'CompTIA A+',
                'IAITAM Certified Software Asset Manager (CSAM)',
            ],
            'responsibilities' => [
                'Hardware and software asset inventory maintenance across all client sites',
                'Software licence tracking, entitlement reconciliation, and compliance reporting',
                'Endpoint configuration baselines and patch compliance monitoring',
                'Device lifecycle planning: procurement, deployment, refresh, and disposal',
                'Warranty and support contract tracking with renewal alerts',
                'Asset documentation, audit evidence packs, and reporting to finance',
            ],
        ],

        [
            'slug' => 'grace-adeyemi',
            'name' => 'Grace Adeyemi',
            'certifications_verified' => false,
            'title' => 'IT Training & Compliance Consultant',
            'practice' => 'training',
            'years' => 14,
            'location' => 'Training & Advisory',
            'tagline' => 'A policy nobody has been trained on is a document, not a control.',
            'initials' => 'GA',
            'photo' => null,
            'bio' => 'Grace has spent fourteen years between the technical and human sides of IT: running security awareness programmes, writing the policies auditors ask for, and training staff who would rather be doing their actual job. She has taken two organisations through ISO 27001 implementation and one through GDPR readiness, and she has learned that the difference between a passing audit and a failing one is usually evidence, not intention. She writes plainly, because a control nobody understands is a control nobody follows.',
            'skills' => [
                'ISO 27001 Implementation', 'GDPR Compliance', 'Security Awareness Training',
                'Policy & Procedure Authoring', 'Risk Assessment', 'Audit Evidence Preparation',
                'Microsoft 365 User Training', 'ITIL Framework Training',
                'Phishing Awareness Programmes', 'Business Continuity Planning',
                'Stakeholder Workshops', 'Documentation & SOPs',
            ],
            'certifications' => [
                'ISO/IEC 27001 Lead Implementer',
                'Certified Information Privacy Professional (CIPP/E)',
                'CompTIA Security+',
                'ITIL 4 Managing Professional',
                'Certified Information Systems Auditor (CISA)',
            ],
            'responsibilities' => [
                'Security awareness and phishing awareness training delivery and tracking',
                'ISO 27001 and GDPR readiness assessment, gap analysis, and implementation support',
                'Security policy, procedure, and acceptable-use documentation authoring',
                'Risk assessment workshops and risk register maintenance',
                'Audit preparation, evidence collation, and assessor liaison',
                'Microsoft 365 and ITIL user training for client teams',
            ],
        ],

        [
            'slug' => 'victor-nakamura',
            'name' => 'Victor Nakamura',
            'certifications_verified' => false,
            'title' => 'Web Hosting & Infrastructure Support Engineer',
            'practice' => 'webops',
            'years' => 12,
            'location' => 'Hosting & Web Ops',
            'tagline' => 'DNS is always the answer, and it is never the answer you want.',
            'initials' => 'VN',
            'photo' => null,
            'bio' => 'Victor has spent twelve years keeping websites and the servers under them alive, starting on a shared hosting support desk where he learned to diagnose a broken site in under five minutes. He now handles domain and DNS architecture, SSL lifecycle management, hosting migrations, and the performance work that decides whether a site survives a traffic spike. He has moved hundreds of sites between providers without losing a mailbox or a certificate, and he keeps a runbook for every one.',
            'skills' => [
                'DNS & Domain Management', 'SSL Certificate Lifecycle', 'cPanel / Plesk',
                'Linux Web Servers', 'Nginx & Apache', 'Website Migration',
                'Web Hosting Setup', 'DDoS Protection', 'CDN Configuration',
                'Website Performance Optimization', 'MySQL / MariaDB', 'Uptime Monitoring',
            ],
            'certifications' => [
                'Linux Professional Institute LPIC-1',
                'AWS Certified Solutions Architect - Associate',
                'CompTIA Linux+',
                'Cloudflare Certified Administrator',
                'cPanel Certified Administrator',
            ],
            'responsibilities' => [
                'Domain registration, DNS zone management, and mail record configuration',
                'SSL/TLS certificate issuance, renewal automation, and expiry monitoring',
                'Website and hosting migration between providers with zero data loss',
                'Web server configuration, reverse proxy setup, and PHP/MySQL troubleshooting',
                'DDoS mitigation, CDN configuration, and uptime monitoring',
                'Website performance tuning, caching strategy, and capacity planning',
            ],
        ],

    ],
];
