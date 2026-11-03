<?php

/**
 * Complete capability / skills taxonomy for the public "Skills & Capabilities"
 * page. Every capability we deliver lives here exactly once so the website can
 * advertise the full breadth of our engineering work.
 *
 * `icon` is a stroke-style SVG path reused by the public skills view.
 *
 * Matching a capability to the sales catalogue (resolved in priority order):
 *   1. Explicit `service_map[<capability slug>]` below (used when the catalogue
 *      service has a different slug from the capability).
 *   2. A service whose `slug` equals the capability slug.
 *   3. Otherwise the capability is shown with "Available on request".
 *
 * Identifiers are database slugs — never display names — so links survive copy
 * changes and stay testable.
 */
return [

    'groups' => [

        [
            'name' => 'IT Service Desk & End-User Support',
            'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'items' => [
                ['slug' => 'it-helpdesk-troubleshooting', 'name' => 'IT Helpdesk Troubleshooting'],
                ['slug' => 'it-ticket-triage-and-prioritization', 'name' => 'IT Ticket Triage & Prioritization'],
                ['slug' => 'remote-it-support', 'name' => 'Remote IT Support'],
                ['slug' => 'desktop-and-laptop-support', 'name' => 'Desktop & Laptop Support'],
                ['slug' => 'windows-troubleshooting', 'name' => 'Windows Troubleshooting'],
                ['slug' => 'macos-troubleshooting', 'name' => 'macOS Troubleshooting'],
                ['slug' => 'linux-desktop-support', 'name' => 'Linux Desktop Support'],
                ['slug' => 'software-installation-and-troubleshooting', 'name' => 'Software Installation & Troubleshooting'],
                ['slug' => 'printer-and-peripheral-support', 'name' => 'Printer & Peripheral Support'],
                ['slug' => 'it-incident-management', 'name' => 'IT Incident Management'],
                ['slug' => 'problem-management-and-root-cause-analysis', 'name' => 'Problem Management & Root-Cause Analysis'],
                ['slug' => 'knowledge-base-and-sop-authoring', 'name' => 'Knowledge-Base & SOP Authoring'],
                ['slug' => 'customer-technical-communication', 'name' => 'Customer Technical Communication'],
                ['slug' => 'it-escalation-and-handoff', 'name' => 'IT Escalation & Handoff'],
                ['slug' => 'it-service-quality-and-documentation', 'name' => 'IT Service Quality & Documentation'],
            ],
        ],

        [
            'name' => 'Servers, Network & Infrastructure',
            'icon' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-5h.01M7 17h.01',
            'items' => [
                ['slug' => 'windows-server-administration', 'name' => 'Windows Server Administration'],
                ['slug' => 'linux-server-administration', 'name' => 'Linux Server Administration'],
                ['slug' => 'active-directory-and-group-policy', 'name' => 'Active Directory & Group Policy'],
                ['slug' => 'identity-access-and-permissions', 'name' => 'Identity, Access & Permissions'],
                ['slug' => 'dns-dhcp-and-directory-services', 'name' => 'DNS, DHCP & Directory Services'],
                ['slug' => 'network-troubleshooting', 'name' => 'Network Troubleshooting'],
                ['slug' => 'firewall-and-network-security', 'name' => 'Firewall & Network Security'],
                ['slug' => 'vpn-and-remote-access', 'name' => 'VPN & Remote Access'],
                ['slug' => 'wifi-and-lan-wan-management', 'name' => 'Wi-Fi, LAN & WAN Management'],
                ['slug' => 'virtualization-and-virtual-machines', 'name' => 'Virtualization & Virtual Machines'],
                ['slug' => 'server-monitoring-and-alerting', 'name' => 'Server Monitoring & Alerting'],
                ['slug' => 'backup-and-disaster-recovery', 'name' => 'Backup & Disaster Recovery'],
                ['slug' => 'patch-management', 'name' => 'Patch Management'],
                ['slug' => 'endpoint-configuration-and-management', 'name' => 'Endpoint Configuration & Management'],
                ['slug' => 'it-asset-and-license-management', 'name' => 'IT Asset & License Management'],
                ['slug' => 'server-hardening', 'name' => 'Server Hardening'],
                ['slug' => 'infrastructure-documentation', 'name' => 'Infrastructure Documentation'],
            ],
        ],

        [
            'name' => 'Microsoft 365 & Business IT',
            'icon' => 'M4 6h16M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6M4 6l8-3 8 3M8 10v6m4-6v6m4-6v6',
            'items' => [
                ['slug' => 'microsoft-365-administration', 'name' => 'Microsoft 365 Administration'],
                ['slug' => 'exchange-online-and-email-support', 'name' => 'Exchange Online & Email Support'],
                ['slug' => 'microsoft-365-migration', 'name' => 'Microsoft 365 Migration'],
                ['slug' => 'microsoft-teams-and-telephony', 'name' => 'Microsoft Teams & Telephony'],
                ['slug' => 'sharepoint-and-onedrive', 'name' => 'SharePoint & OneDrive'],
                ['slug' => 'entra-id-and-identity-management', 'name' => 'Entra ID & Identity Management'],
                ['slug' => 'intune-and-device-management', 'name' => 'Intune & Device Management'],
                ['slug' => 'microsoft-365-security-and-compliance', 'name' => 'Microsoft 365 Security & Compliance'],
                ['slug' => 'business-email-delivery-and-dns', 'name' => 'Business Email Delivery & DNS'],
                ['slug' => 'microsoft-365-user-training', 'name' => 'Microsoft 365 User Training'],
            ],
        ],

        [
            'name' => 'Cloud, DevOps & Hosting',
            'icon' => 'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z',
            'items' => [
                ['slug' => 'cloud-architecture-and-migration', 'name' => 'Cloud Architecture & Migration'],
                ['slug' => 'aws-administration', 'name' => 'AWS Administration'],
                ['slug' => 'azure-administration', 'name' => 'Azure Administration'],
                ['slug' => 'google-cloud-fundamentals', 'name' => 'Google Cloud Fundamentals'],
                ['slug' => 'cloud-cost-optimization', 'name' => 'Cloud Cost Optimization'],
                ['slug' => 'cloud-security-and-identity', 'name' => 'Cloud Security & Identity'],
                ['slug' => 'web-hosting-and-domain-management', 'name' => 'Web Hosting & Domain Management'],
                ['slug' => 'ssl-tls-and-certificate-management', 'name' => 'SSL, TLS & Certificate Management'],
                ['slug' => 'docker-and-containerization', 'name' => 'Docker & Containerization'],
                ['slug' => 'kubernetes-deployment-and-operations', 'name' => 'Kubernetes Deployment & Operations'],
                ['slug' => 'ci-cd-and-devops-automation', 'name' => 'CI/CD & DevOps Automation'],
                ['slug' => 'linux-web-server-and-reverse-proxy', 'name' => 'Linux Web Server & Reverse Proxy'],
                ['slug' => 'monitoring-logging-and-observability', 'name' => 'Monitoring, Logging & Observability'],
                ['slug' => 'infrastructure-as-code', 'name' => 'Infrastructure as Code'],
            ],
        ],

        [
            'name' => 'Cybersecurity',
            'icon' => 'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3zM9.2 12l1.9 1.9L15 10',
            'items' => [
                ['slug' => 'cybersecurity-incident-response', 'name' => 'Cybersecurity Incident Response'],
                ['slug' => 'vulnerability-assessment-and-remediation', 'name' => 'Vulnerability Assessment & Remediation'],
                ['slug' => 'authorized-penetration-testing', 'name' => 'Authorized Penetration Testing'],
                ['slug' => 'web-application-security', 'name' => 'Web Application Security'],
                ['slug' => 'api-security', 'name' => 'API Security'],
                ['slug' => 'network-security-assessment', 'name' => 'Network Security Assessment'],
                ['slug' => 'cloud-security-assessment', 'name' => 'Cloud Security Assessment'],
                ['slug' => 'security-hardening-and-baselines', 'name' => 'Security Hardening & Baselines'],
                ['slug' => 'endpoint-detection-and-response', 'name' => 'Endpoint Detection & Response'],
                ['slug' => 'siem-monitoring-and-alert-triage', 'name' => 'SIEM Monitoring & Alert Triage'],
                ['slug' => 'malware-triage-and-containment', 'name' => 'Malware Triage & Containment'],
                ['slug' => 'email-security-and-phishing', 'name' => 'Email Security & Phishing'],
                ['slug' => 'identity-threat-detection', 'name' => 'Identity Threat Detection'],
                ['slug' => 'security-awareness-and-phishing-simulation', 'name' => 'Security Awareness & Phishing Simulation'],
                ['slug' => 'risk-assessment-and-security-reporting', 'name' => 'Risk Assessment & Security Reporting'],
                ['slug' => 'security-policy-and-compliance-support', 'name' => 'Security Policy & Compliance Support'],
                ['slug' => 'owasp-security-review', 'name' => 'OWASP Security Review'],
                ['slug' => 'wordpress-security-and-malware-cleanup', 'name' => 'WordPress Security & Malware Cleanup'],
                ['slug' => 'penetration-test-report-writing', 'name' => 'Penetration Test Report Writing'],
                ['slug' => 'security-logging-and-audit-readiness', 'name' => 'Security Logging & Audit Readiness'],
            ],
        ],

        [
            'name' => 'Web & Software Development',
            'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
            'items' => [
                ['slug' => 'website-development-and-maintenance', 'name' => 'Website Development & Maintenance'],
                ['slug' => 'wordpress-development-and-troubleshooting', 'name' => 'WordPress Development & Troubleshooting'],
                ['slug' => 'laravel-and-php-development', 'name' => 'Laravel & PHP Development'],
                ['slug' => 'react-and-frontend-development', 'name' => 'React & Frontend Development'],
                ['slug' => 'vue-and-frontend-development', 'name' => 'Vue & Frontend Development'],
                ['slug' => 'nodejs-backend-development', 'name' => 'Node.js Backend Development'],
                ['slug' => 'python-and-django-development', 'name' => 'Python & Django Development'],
                ['slug' => 'api-development-and-integration', 'name' => 'API Development & Integration'],
                ['slug' => 'web-application-debugging', 'name' => 'Web Application Debugging'],
                ['slug' => 'application-performance-optimization', 'name' => 'Application Performance Optimization'],
                ['slug' => 'database-design-and-administration', 'name' => 'Database Design & Administration'],
                ['slug' => 'sql-debugging-and-query-optimization', 'name' => 'SQL Debugging & Query Optimization'],
                ['slug' => 'website-migration-and-deployment', 'name' => 'Website Migration & Deployment'],
                ['slug' => 'git-and-source-control', 'name' => 'Git & Source Control'],
                ['slug' => 'secure-software-development', 'name' => 'Secure Software Development'],
                ['slug' => 'mobile-app-development-support', 'name' => 'Mobile App Development Support'],
                ['slug' => 'website-testing-and-quality-assurance', 'name' => 'Website Testing & Quality Assurance'],
            ],
        ],

        [
            'name' => 'IT Automation & Scripting',
            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
            'items' => [
                ['slug' => 'powershell-it-automation', 'name' => 'PowerShell IT Automation'],
                ['slug' => 'python-it-automation', 'name' => 'Python IT Automation'],
                ['slug' => 'bash-linux-automation', 'name' => 'Bash & Linux Automation'],
                ['slug' => 'windows-task-and-job-automation', 'name' => 'Windows Task & Job Automation'],
                ['slug' => 'api-integration-and-webhooks', 'name' => 'API Integration & Webhooks'],
                ['slug' => 'workflow-automation', 'name' => 'Workflow Automation'],
                ['slug' => 'zapier-automation', 'name' => 'Zapier Automation'],
                ['slug' => 'power-automate', 'name' => 'Power Automate'],
                ['slug' => 'data-processing-and-csv-automation', 'name' => 'Data Processing & CSV Automation'],
                ['slug' => 'log-analysis-and-automated-diagnostics', 'name' => 'Log Analysis & Automated Diagnostics'],
                ['slug' => 'automated-reporting', 'name' => 'Automated Reporting'],
                ['slug' => 'script-testing-and-code-review', 'name' => 'Script Testing & Code Review'],
                ['slug' => 'secure-secrets-and-configuration-management', 'name' => 'Secure Secrets & Configuration Management'],
            ],
        ],

        [
            'name' => 'AI Agents & Applied AI',
            'icon' => 'M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z',
            'items' => [
                ['slug' => 'ai-agent-architecture-and-design', 'name' => 'AI Agent Architecture & Design'],
                ['slug' => 'ai-agent-development', 'name' => 'AI Agent Development'],
                ['slug' => 'ai-agent-deployment-and-configuration', 'name' => 'AI Agent Deployment & Configuration'],
                ['slug' => 'ai-agent-testing-and-evaluation', 'name' => 'AI Agent Testing & Evaluation'],
                ['slug' => 'ai-agent-monitoring-and-maintenance', 'name' => 'AI Agent Monitoring & Maintenance'],
                ['slug' => 'claude-desktop-configuration', 'name' => 'Claude Desktop Configuration'],
                ['slug' => 'claude-skills-creation-and-maintenance', 'name' => 'Claude Skills Creation & Maintenance'],
                ['slug' => 'claude-mcp-server-configuration', 'name' => 'Claude MCP Server Configuration'],
                ['slug' => 'mcp-server-development-and-debugging', 'name' => 'MCP Server Development & Debugging'],
                ['slug' => 'llm-api-integration', 'name' => 'LLM API Integration'],
                ['slug' => 'rag-and-knowledge-base-development', 'name' => 'RAG & Knowledge-Base Development'],
                ['slug' => 'vector-search-and-embeddings', 'name' => 'Vector Search & Embeddings'],
                ['slug' => 'ai-chatbot-development', 'name' => 'AI Chatbot Development'],
                ['slug' => 'customer-support-ai-agent', 'name' => 'Customer Support AI Agent'],
                ['slug' => 'it-helpdesk-ai-agent', 'name' => 'IT Helpdesk AI Agent'],
                ['slug' => 'multi-agent-orchestration', 'name' => 'Multi-Agent Orchestration'],
                ['slug' => 'ai-workflow-automation', 'name' => 'AI Workflow Automation'],
                ['slug' => 'ai-tool-use-and-permissions', 'name' => 'AI Tool Use & Permissions'],
                ['slug' => 'ai-agent-security-and-prompt-injection-defense', 'name' => 'AI Agent Security & Prompt-Injection Defense'],
                ['slug' => 'ai-agent-evaluation-and-observability', 'name' => 'AI Agent Evaluation & Observability'],
                ['slug' => 'local-ai-model-deployment', 'name' => 'Local AI Model Deployment'],
                ['slug' => 'ai-cost-latency-and-reliability-optimization', 'name' => 'AI Cost, Latency & Reliability Optimization'],
                ['slug' => 'ai-agent-documentation-and-handover', 'name' => 'AI Agent Documentation & Handover'],
            ],
        ],

        [
            'name' => 'Business Systems & Data',
            'icon' => 'M4 7c0-1.657 3.582-3 8-3s8 1.343 8 3-3.582 3-8 3-8-1.343-8-3zM4 7v10c0 1.657 3.582 3 8 3s8-1.343 8-3V7M4 12c0 1.657 3.582 3 8 3s8-1.343 8-3',
            'items' => [
                ['slug' => 'crm-setup-and-customization', 'name' => 'CRM Setup & Customization'],
                ['slug' => 'erp-and-business-systems', 'name' => 'ERP & Business Systems'],
                ['slug' => 'business-process-automation', 'name' => 'Business Process Automation'],
                ['slug' => 'reporting-and-dashboard-development', 'name' => 'Reporting & Dashboard Development'],
                ['slug' => 'power-bi-and-business-intelligence', 'name' => 'Power BI & Business Intelligence'],
                ['slug' => 'tableau-and-data-visualization', 'name' => 'Tableau & Data Visualization'],
                ['slug' => 'data-warehouse-and-data-integration', 'name' => 'Data Warehouse & Data Integration'],
                ['slug' => 'excel-and-spreadsheet-automation', 'name' => 'Excel & Spreadsheet Automation'],
                ['slug' => 'data-migration-and-data-quality', 'name' => 'Data Migration & Data Quality'],
            ],
        ],

        [
            'name' => 'E-commerce & Digital Marketing',
            'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z',
            'items' => [
                ['slug' => 'shopify-store-support', 'name' => 'Shopify Store Support'],
                ['slug' => 'woocommerce-and-wordpress-commerce', 'name' => 'WooCommerce & WordPress Commerce'],
                ['slug' => 'payment-gateway-integration', 'name' => 'Payment Gateway Integration'],
                ['slug' => 'ecommerce-troubleshooting-and-performance', 'name' => 'E-commerce Troubleshooting & Performance'],
                ['slug' => 'seo-and-technical-seo', 'name' => 'SEO & Technical SEO'],
                ['slug' => 'digital-marketing-analytics', 'name' => 'Digital Marketing Analytics'],
                ['slug' => 'marketing-automation-and-lead-generation', 'name' => 'Marketing Automation & Lead Generation'],
            ],
        ],

        [
            'name' => 'Managed IT & Service Management',
            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'items' => [
                ['slug' => 'business-technology-consulting', 'name' => 'Business Technology Consulting'],
                ['slug' => 'it-training-and-user-documentation', 'name' => 'IT Training & User Documentation'],
                ['slug' => 'gdpr-and-iso27001-readiness-support', 'name' => 'GDPR & ISO 27001 Readiness Support'],
                ['slug' => 'customer-onboarding-and-offboarding', 'name' => 'Customer Onboarding & Offboarding'],
                ['slug' => 'customer-environment-inventory', 'name' => 'Customer Environment Inventory'],
                ['slug' => 'managed-it-service-operations', 'name' => 'Managed IT Service Operations'],
                ['slug' => 'service-request-fulfillment', 'name' => 'Service Request Fulfillment'],
                ['slug' => 'sla-and-service-reporting', 'name' => 'SLA & Service Reporting'],
                ['slug' => 'it-change-management', 'name' => 'IT Change Management'],
                ['slug' => 'maintenance-window-planning', 'name' => 'Maintenance Window Planning'],
                ['slug' => 'capacity-and-performance-planning', 'name' => 'Capacity & Performance Planning'],
                ['slug' => 'customer-technical-proposals', 'name' => 'Customer Technical Proposals'],
                ['slug' => 'technical-estimation-and-scope-definition', 'name' => 'Technical Estimation & Scope Definition'],
                ['slug' => 'service-delivery-documentation', 'name' => 'Service Delivery Documentation'],
                ['slug' => 'it-business-continuity-planning', 'name' => 'IT Business Continuity Planning'],
                ['slug' => 'continuous-improvement-and-automation-discovery', 'name' => 'Continuous Improvement & Automation Discovery'],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Capability → catalogue service mapping
    |--------------------------------------------------------------------------
    | Only capabilities whose catalogue service uses a DIFFERENT slug than the
    | capability need an entry here. Everything else resolves by matching slug
    | (see the resolution order in the file docblock above).
    |
    | Keys are capability slugs; values are existing `services.slug` values.
    */
    'service_map' => [
        // IT Service Desk & End-User Support
        'it-ticket-triage-and-prioritization' => 'it-ticket-triage-prioritization',
        'desktop-and-laptop-support' => 'desktop-support',
        'windows-troubleshooting' => 'windows-troubleshooting-support',
        'macos-troubleshooting' => 'macos-troubleshooting-support',
        'software-installation-and-troubleshooting' => 'software-troubleshooting',
        'printer-and-peripheral-support' => 'printer-peripheral-support',
        'problem-management-and-root-cause-analysis' => 'problem-management-root-cause-analysis',
        'knowledge-base-and-sop-authoring' => 'knowledge-base-sop-authoring',
        'it-escalation-and-handoff' => 'it-escalation-handoff',
        'it-service-quality-and-documentation' => 'it-service-quality-documentation',

        // Servers, Network & Infrastructure
        'active-directory-and-group-policy' => 'active-directory-group-policy',
        'identity-access-and-permissions' => 'identity-access-permissions-management',
        'dns-dhcp-and-directory-services' => 'dns-dhcp-directory-services',
        'firewall-and-network-security' => 'firewall-security-review',
        'vpn-and-remote-access' => 'vpn-setup',
        'wifi-and-lan-wan-management' => 'wi-fi-lan-wan-management',
        'virtualization-and-virtual-machines' => 'virtual-server-setup',
        'server-monitoring-and-alerting' => 'server-monitoring-setup',
        'backup-and-disaster-recovery' => 'backup-management',
        'endpoint-configuration-and-management' => 'endpoint-configuration-maintenance',
        'it-asset-and-license-management' => 'computer-device-inventory-audit',

        // Microsoft 365 & Business IT
        'exchange-online-and-email-support' => 'exchange-online-email-support',
        'microsoft-365-migration' => 'email-migration-to-m365',
        'microsoft-teams-and-telephony' => 'teams-phone-system-setup',
        'sharepoint-and-onedrive' => 'sharepoint-development',
        'entra-id-and-identity-management' => 'azure-ad-identity-management',
        'intune-and-device-management' => 'intune-mdm',
        'microsoft-365-security-and-compliance' => 'microsoft-365-security-compliance',
        'business-email-delivery-and-dns' => 'business-email-delivery-dns-support',

        // Cloud, DevOps & Hosting
        'cloud-architecture-and-migration' => 'cloud-migration',
        'cloud-security-and-identity' => 'cloud-security-configuration',
        'web-hosting-and-domain-management' => 'web-hosting-setup',
        'ssl-tls-and-certificate-management' => 'ssl-certificate-management',
        'docker-and-containerization' => 'docker-containerization',
        'kubernetes-deployment-and-operations' => 'kubernetes-deployment',
        'ci-cd-and-devops-automation' => 'cicd-devops-automation',
        'linux-web-server-and-reverse-proxy' => 'linux-web-server-reverse-proxy',
        'monitoring-logging-and-observability' => 'monitoring-logging-observability',
        'infrastructure-as-code' => 'infrastructure-as-code-iac',

        // Cybersecurity
        'cybersecurity-incident-response' => 'incident-response-support',
        'vulnerability-assessment-and-remediation' => 'vulnerability-assessment',
        'authorized-penetration-testing' => 'penetration-testing',
        'web-application-security' => 'web-application-security-assessment',
        'api-security' => 'api-security-testing',
        'security-hardening-and-baselines' => 'security-hardening',
        'endpoint-detection-and-response' => 'endpoint-detection-response-edr',
        'siem-monitoring-and-alert-triage' => 'siem-monitoring-alert-triage',
        'malware-triage-and-containment' => 'malware-incident-investigation',
        'email-security-and-phishing' => 'email-security-configuration',
        'security-awareness-and-phishing-simulation' => 'security-awareness-training',
        'risk-assessment-and-security-reporting' => 'risk-assessment',
        'security-policy-and-compliance-support' => 'security-policy-development',
        'wordpress-security-and-malware-cleanup' => 'wordpress-security-audit',
        'penetration-test-report-writing' => 'pentest-report',
        'security-logging-and-audit-readiness' => 'security-logging-audit-readiness',

        // Web & Software Development
        'website-development-and-maintenance' => 'business-website-development',
        'wordpress-development-and-troubleshooting' => 'wordpress-development',
        'laravel-and-php-development' => 'laravel-development',
        'react-and-frontend-development' => 'reactjs-development',
        'vue-and-frontend-development' => 'vuejs-development',
        'nodejs-backend-development' => 'nodejs-backend',
        'python-and-django-development' => 'python-django',
        'api-development-and-integration' => 'api-development',
        'web-application-debugging' => 'website-bug-fixing',
        'application-performance-optimization' => 'website-performance-optimization',
        'database-design-and-administration' => 'database-design-development',
        'sql-debugging-and-query-optimization' => 'sql-optimization',
        'website-migration-and-deployment' => 'website-migration',
        'git-and-source-control' => 'git-source-control',
        'mobile-app-development-support' => 'flutter-mobile-app',
        'website-testing-and-quality-assurance' => 'website-testing-quality-assurance',

        // IT Automation & Scripting
        'windows-task-and-job-automation' => 'windows-task-job-automation',
        'api-integration-and-webhooks' => 'api-integration-webhooks',
        'data-processing-and-csv-automation' => 'data-processing-csv-automation',
        'log-analysis-and-automated-diagnostics' => 'log-analysis-automated-diagnostics',
        'script-testing-and-code-review' => 'script-testing-code-review',
        'secure-secrets-and-configuration-management' => 'secure-secrets-configuration-management',

        // AI Agents & Applied AI
        'ai-agent-architecture-and-design' => 'ai-agent-architecture-design',
        'ai-agent-deployment-and-configuration' => 'ai-agent-deployment-configuration',
        'ai-agent-testing-and-evaluation' => 'ai-agent-testing-evaluation',
        'ai-agent-monitoring-and-maintenance' => 'ai-agent-monitoring-maintenance',
        'claude-skills-creation-and-maintenance' => 'claude-skills-creation-maintenance',
        'mcp-server-development-and-debugging' => 'mcp-server-development-debugging',
        'rag-and-knowledge-base-development' => 'rag-knowledge-base-development',
        'vector-search-and-embeddings' => 'vector-search-embeddings',
        'ai-chatbot-development' => 'ai-chatbot-integration',
        'ai-tool-use-and-permissions' => 'ai-tool-use-permissions',
        'ai-agent-security-and-prompt-injection-defense' => 'ai-agent-security-prompt-injection-defense',
        'ai-agent-evaluation-and-observability' => 'ai-agent-evaluation-observability',
        'ai-cost-latency-and-reliability-optimization' => 'ai-cost-latency-reliability-optimization',
        'ai-agent-documentation-and-handover' => 'ai-agent-documentation-handover',

        // Business Systems & Data
        'crm-setup-and-customization' => 'crm-setup',
        'erp-and-business-systems' => 'erp-implementation',
        'reporting-and-dashboard-development' => 'reporting-dashboard-development',
        'power-bi-and-business-intelligence' => 'power-bi-development',
        'tableau-and-data-visualization' => 'tableau-dashboard',
        'data-warehouse-and-data-integration' => 'data-warehouse-setup',
        'excel-and-spreadsheet-automation' => 'excel-spreadsheet-automation',
        'data-migration-and-data-quality' => 'data-migration-services',

        // E-commerce & Digital Marketing
        'shopify-store-support' => 'shopify-store-setup',
        'woocommerce-and-wordpress-commerce' => 'woocommerce-development',
        'ecommerce-troubleshooting-and-performance' => 'e-commerce-troubleshooting-performance',
        'seo-and-technical-seo' => 'seo-audit',
        'digital-marketing-analytics' => 'analytics-and-tracking-setup',
        'marketing-automation-and-lead-generation' => 'marketing-automation',

        // Managed IT & Service Management
        'business-technology-consulting' => 'business-it-consultation',
        'it-training-and-user-documentation' => 'it-training-user-documentation',
        'gdpr-and-iso27001-readiness-support' => 'gdpr-compliance-consulting',
        'customer-onboarding-and-offboarding' => 'customer-onboarding-offboarding',
        'customer-environment-inventory' => 'it-infrastructure-review',
        'service-request-fulfillment' => 'service-request-fulfilment',
        'sla-and-service-reporting' => 'sla-service-reporting',
        'capacity-and-performance-planning' => 'capacity-performance-planning',
        'technical-estimation-and-scope-definition' => 'technical-estimation-scope-definition',
        'continuous-improvement-and-automation-discovery' => 'continuous-improvement-automation-discovery',
    ],

];
