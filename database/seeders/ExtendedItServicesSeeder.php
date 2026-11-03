<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCountryPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * ExtendedItServicesSeeder — completes the IT service catalogue by adding the
 * remaining offerings from the 161-item IT service taxonomy that were not yet
 * represented on the public services page.
 *
 * Adds 4 new categories + 82 services (the 55 missing items and the 27 items
 * that previously only had a loosely related service).
 *
 * Idempotent: categories are matched by slug, services by slug, and country
 * prices by (service, country). Safe to re-run — no duplicates.
 * Prices are indicative placeholder estimates (UK/US/BD); edit real prices in
 * the admin -> Services screens afterwards.
 */
class ExtendedItServicesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'it-service-management',
                'name' => 'IT Service Management & Operations',
                'description' => 'ITIL-aligned service management: helpdesk, triage, incident, problem, change, SLA, service requests, onboarding and service quality.',
                'icon' => 'itsm',
                'color' => '#1D4ED8',
                'sort_order' => 13,
            ],
            [
                'slug' => 'desktop-endpoint-support',
                'name' => 'Desktop, Endpoint & Device Support',
                'description' => 'End-user computing support for Windows, macOS and Linux desktops, printers and peripherals.',
                'icon' => 'desktop',
                'color' => '#0F766E',
                'sort_order' => 14,
            ],
            [
                'slug' => 'devops-automation',
                'name' => 'DevOps, Scripting & Automation',
                'description' => 'CI/CD, infrastructure as code, scripting, source control, automated testing and secure delivery.',
                'icon' => 'devops',
                'color' => '#B45309',
                'sort_order' => 15,
            ],
            [
                'slug' => 'ai-agents',
                'name' => 'AI Agents & LLM Engineering',
                'description' => 'AI agent architecture, development, deployment, evaluation, security and LLM/RAG engineering.',
                'icon' => 'ai',
                'color' => '#6D28D9',
                'sort_order' => 16,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true]
            );
        }

        $countryIds = [
            'UK' => Country::where('code', 'UK')->value('id'),
            'US' => Country::where('code', 'US')->value('id'),
            'BD' => Country::where('code', 'BD')->value('id'),
        ];
        if (in_array(null, $countryIds, true)) {
            $this->command?->warn('UK/US/BD country rows missing — skipping extended IT services.');

            return;
        }

        $services = [
            // ── IT Service Management & Operations ─────────────────────────────
            ['name' => 'IT Helpdesk Troubleshooting', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 149, 'us' => 179, 'bd' => 17500, 'cx' => 'standard', 'eta' => 'Same–next business day',
                'short' => 'First-line helpdesk troubleshooting for everyday user and desktop issues.',
                'desc' => 'First-line helpdesk troubleshooting covering common end-user issues: account access, application errors, connectivity, and how-to guidance, resolved remotely or routed onward with a clear record.',
                'feat' => ['First-line diagnosis', 'Remote resolution', 'Ticket documentation'], 'tags' => ['helpdesk', 'troubleshooting', 'support']],
            ['name' => 'IT Ticket Triage & Prioritization', 'cat' => 'it-service-management', 'pt' => 'fixed', 'uk' => 199, 'us' => 239, 'bd' => 23500, 'cx' => 'standard', 'eta' => '3–5 business days',
                'short' => 'Design and configure a triage and priority model for your support queue.',
                'desc' => 'We design categories, priorities, impact/urgency matrices and routing rules so tickets are triaged consistently and the right work reaches the right owner first.',
                'feat' => ['Priority matrix design', 'Category and routing rules', 'SLA alignment'], 'tags' => ['triage', 'prioritization', 'itil']],
            ['name' => 'IT Incident Management', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 449, 'us' => 549, 'bd' => 50000, 'cx' => 'standard', 'eta' => 'Ongoing / per incident',
                'short' => 'Structured incident response processes to restore service fast.',
                'desc' => 'Incident management processes covering detection, logging, prioritization, communication, restoration and post-incident review aligned to ITIL practice.',
                'feat' => ['Incident workflow design', 'Escalation paths', 'Post-incident review'], 'tags' => ['incident', 'itil', 'service-desk']],
            ['name' => 'Problem Management & Root Cause Analysis', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 499, 'us' => 599, 'bd' => 55000, 'cx' => 'advanced', 'eta' => '1–2 weeks',
                'short' => 'Investigate recurring incidents and eliminate root causes.',
                'desc' => 'Problem management to correlate repeated incidents, perform root cause analysis, track known errors and drive permanent fixes and workarounds.',
                'feat' => ['Root cause analysis', 'Known error database', 'Permanent fix plan'], 'tags' => ['problem-management', 'rca', 'itil']],
            ['name' => 'IT Escalation & Handoff', 'cat' => 'it-service-management', 'pt' => 'fixed', 'uk' => 179, 'us' => 219, 'bd' => 21000, 'cx' => 'standard', 'eta' => '3–5 business days',
                'short' => 'Define escalation tiers and clean handoff procedures.',
                'desc' => 'We document escalation thresholds, ownership and handoff checklists so tickets pass between tiers without losing context or breaching SLAs.',
                'feat' => ['Escalation matrix', 'Handoff checklists', 'Ownership rules'], 'tags' => ['escalation', 'handoff', 'sla']],
            ['name' => 'IT Service Quality & Documentation', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 349, 'us' => 419, 'bd' => 40000, 'cx' => 'standard', 'eta' => '2–4 weeks',
                'short' => 'Measure and improve service quality with documentation standards.',
                'desc' => 'Establish service quality metrics, QA reviews and documentation standards so support delivery is consistent, measurable and auditable.',
                'feat' => ['Quality metrics', 'QA review process', 'Documentation standards'], 'tags' => ['quality', 'documentation', 'itil']],
            ['name' => 'Knowledge Base & SOP Authoring', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 299, 'us' => 359, 'bd' => 35000, 'cx' => 'standard', 'eta' => '1–3 weeks',
                'short' => 'Write knowledge base articles and standard operating procedures.',
                'desc' => 'We author clear, searchable knowledge base articles and SOPs so recurring issues are resolved consistently and new staff onboard faster.',
                'feat' => ['KB article writing', 'SOP documents', 'Deflection-ready content'], 'tags' => ['knowledge-base', 'sop', 'documentation']],
            ['name' => 'Customer Technical Communication', 'cat' => 'it-service-management', 'pt' => 'fixed', 'uk' => 199, 'us' => 249, 'bd' => 23500, 'cx' => 'standard', 'eta' => '3–5 business days',
                'short' => 'Templates and coaching for clear customer-facing technical updates.',
                'desc' => 'We build communication templates and guidance so status updates, incidents and resolutions are explained to customers clearly and without jargon.',
                'feat' => ['Update templates', 'Plain-language guidance', 'Escalation comms'], 'tags' => ['communication', 'customer', 'support']],
            ['name' => 'SLA & Service Reporting', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 449, 'us' => 549, 'bd' => 50000, 'cx' => 'standard', 'eta' => '1–2 weeks',
                'short' => 'Define SLAs and produce regular service performance reports.',
                'desc' => 'We help define realistic SLAs and build recurring service reports covering response, resolution, breaches and trends for stakeholders.',
                'feat' => ['SLA definition', 'Compliance reporting', 'Trend analysis'], 'tags' => ['sla', 'reporting', 'service']],
            ['name' => 'Service Request Fulfilment', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 249, 'us' => 299, 'bd' => 29500, 'cx' => 'standard', 'eta' => 'Per request',
                'short' => 'Standardized fulfilment of routine service requests.',
                'desc' => 'A repeatable workflow for common service requests — access, provisioning, changes and standard tasks — with approvals, ownership and completion records.',
                'feat' => ['Request catalog', 'Approval workflow', 'Fulfilment tracking'], 'tags' => ['service-request', 'fulfilment', 'itil']],
            ['name' => 'IT Change Management', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 399, 'us' => 479, 'bd' => 45000, 'cx' => 'standard', 'eta' => '1–2 weeks',
                'short' => 'Controlled change process with approvals and rollback plans.',
                'desc' => 'Change management covering change types, risk classification, CAB-style approval, scheduling, rollback planning and post-change verification.',
                'feat' => ['Change classification', 'Approval workflow', 'Rollback planning'], 'tags' => ['change-management', 'itil', 'risk']],
            ['name' => 'Maintenance Window Planning', 'cat' => 'it-service-management', 'pt' => 'fixed', 'uk' => 249, 'us' => 299, 'bd' => 29500, 'cx' => 'standard', 'eta' => '1 week',
                'short' => 'Plan and schedule safe maintenance windows with minimal disruption.',
                'desc' => 'We plan maintenance windows with impact assessment, notifications, sequencing and verification so patching and upgrades run without surprises.',
                'feat' => ['Impact assessment', 'Notification plan', 'Verification checklist'], 'tags' => ['maintenance', 'planning', 'change']],
            ['name' => 'Capacity & Performance Planning', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 549, 'us' => 659, 'bd' => 60000, 'cx' => 'advanced', 'eta' => '2–3 weeks',
                'short' => 'Forecast capacity needs and plan for performance headroom.',
                'desc' => 'Capacity planning covering current utilisation, growth forecasting, bottleneck analysis and a scaling roadmap for compute, storage and network.',
                'feat' => ['Utilization baseline', 'Growth forecast', 'Scaling roadmap'], 'tags' => ['capacity', 'performance', 'planning']],
            ['name' => 'Customer Onboarding & Offboarding', 'cat' => 'it-service-management', 'pt' => 'fixed', 'uk' => 299, 'us' => 359, 'bd' => 35000, 'cx' => 'standard', 'eta' => 'Per cycle',
                'short' => 'Structured joiner/leaver IT provisioning and de-provisioning.',
                'desc' => 'Onboarding and offboarding runbooks for accounts, devices, access and data — ensuring new users are productive and departing users leave no access behind.',
                'feat' => ['Joiner checklist', 'Leaver checklist', 'Access review'], 'tags' => ['onboarding', 'offboarding', 'identity']],
            ['name' => 'Customer Technical Proposals', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 399, 'us' => 479, 'bd' => 45000, 'cx' => 'standard', 'eta' => '3–7 business days',
                'short' => 'Professional technical proposals and solution documents for clients.',
                'desc' => 'We produce structured technical proposals — scope, approach, assumptions, deliverables and commercials — ready to present to customer stakeholders.',
                'feat' => ['Solution write-up', 'Scope and assumptions', 'Commercial summary'], 'tags' => ['proposal', 'presales', 'consulting']],
            ['name' => 'Technical Estimation & Scope Definition', 'cat' => 'it-service-management', 'pt' => 'fixed', 'uk' => 349, 'us' => 419, 'bd' => 40000, 'cx' => 'advanced', 'eta' => '3–7 business days',
                'short' => 'Turn rough requirements into a costed, agreed scope.',
                'desc' => 'We define scope, assumptions, effort estimates and dependencies so projects start with realistic expectations and fewer change requests.',
                'feat' => ['Scope statement', 'Effort estimate', 'Assumptions log'], 'tags' => ['estimation', 'scope', 'presales']],
            ['name' => 'Service Delivery Documentation', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 349, 'us' => 419, 'bd' => 40000, 'cx' => 'standard', 'eta' => '1–3 weeks',
                'short' => 'Delivery runbooks, handover packs and acceptance documentation.',
                'desc' => 'We produce service delivery documentation: runbooks, handover packs, acceptance criteria and as-built records for smooth operational handover.',
                'feat' => ['Operational runbooks', 'Handover pack', 'Acceptance records'], 'tags' => ['documentation', 'delivery', 'handover']],
            ['name' => 'Continuous Improvement & Automation Discovery', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 399, 'us' => 479, 'bd' => 45000, 'cx' => 'standard', 'eta' => '2–4 weeks',
                'short' => 'Identify repetitive work and build a prioritized automation roadmap.',
                'desc' => 'We review your operations for waste and repetitive tasks, then produce a prioritized continuous-improvement and automation opportunity roadmap.',
                'feat' => ['Process review', 'Automation candidates', 'Prioritized roadmap'], 'tags' => ['improvement', 'automation', 'process']],
            ['name' => 'IT Business Continuity Planning', 'cat' => 'it-service-management', 'pt' => 'starting_from', 'uk' => 749, 'us' => 899, 'bd' => 85000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Plan how the business keeps running through major disruption.',
                'desc' => 'Business continuity planning covering critical functions, dependencies, recovery objectives (RTO/RPO), alternate arrangements and validation exercises.',
                'feat' => ['Critical function mapping', 'RTO/RPO objectives', 'Continuity exercises'], 'tags' => ['continuity', 'resilience', 'planning']],
            ['name' => 'Managed IT Service Operations', 'cat' => 'it-service-management', 'pt' => 'recurring', 'uk' => 450, 'us' => 540, 'bd' => 50000, 'cx' => 'standard', 'eta' => 'Monthly retainer',
                'short' => 'Ongoing management of your IT service operations and support function.',
                'desc' => 'A monthly service-operations retainer covering queue management, reporting, service reviews, escalation ownership and continual improvement across your IT support function.',
                'feat' => ['Service desk ownership', 'Monthly service reviews', 'Continual improvement'], 'tags' => ['managed', 'operations', 'retainer']],

            // ── Desktop, Endpoint & Device Support ─────────────────────────────
            ['name' => 'Windows Troubleshooting & Support', 'cat' => 'desktop-endpoint-support', 'pt' => 'starting_from', 'uk' => 129, 'us' => 159, 'bd' => 15000, 'cx' => 'standard', 'eta' => 'Same–next business day',
                'short' => 'Windows desktop and laptop troubleshooting and repair.',
                'desc' => 'Windows 10/11 troubleshooting covering boot issues, errors, updates, performance, drivers and application faults, resolved remotely or with onsite options.',
                'feat' => ['Boot and error diagnosis', 'Update and driver fixes', 'Performance tuning'], 'tags' => ['windows', 'desktop', 'troubleshooting']],
            ['name' => 'macOS Troubleshooting & Support', 'cat' => 'desktop-endpoint-support', 'pt' => 'starting_from', 'uk' => 149, 'us' => 179, 'bd' => 17500, 'cx' => 'standard', 'eta' => 'Same–next business day',
                'short' => 'macOS device troubleshooting, setup and support.',
                'desc' => 'macOS support covering setup, software issues, iCloud/account problems, performance, updates and integration with your business systems.',
                'feat' => ['macOS diagnostics', 'App and account fixes', 'Business integration'], 'tags' => ['macos', 'apple', 'support']],
            ['name' => 'Linux Desktop Support', 'cat' => 'desktop-endpoint-support', 'pt' => 'starting_from', 'uk' => 149, 'us' => 179, 'bd' => 17500, 'cx' => 'standard', 'eta' => 'Same–next business day',
                'short' => 'Support for Linux desktop and workstation environments.',
                'desc' => 'Linux desktop support covering installation, package management, drivers, desktop environments and troubleshooting for Ubuntu, Fedora and similar distributions.',
                'feat' => ['Distro setup', 'Package and driver fixes', 'Desktop troubleshooting'], 'tags' => ['linux', 'desktop', 'support']],
            ['name' => 'Printer & Peripheral Support', 'cat' => 'desktop-endpoint-support', 'pt' => 'starting_from', 'uk' => 99, 'us' => 129, 'bd' => 12000, 'cx' => 'standard', 'eta' => 'Same–next business day',
                'short' => 'Printer, scanner and peripheral troubleshooting and setup.',
                'desc' => 'Setup and troubleshooting for printers, scanners, docking stations, monitors and other peripherals, including drivers, network printing and queue issues.',
                'feat' => ['Driver and queue fixes', 'Network printing', 'Peripheral setup'], 'tags' => ['printer', 'peripherals', 'hardware']],

            // ── Cloud, Server & Network ────────────────────────────────────────
            ['name' => 'Network Troubleshooting', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 179, 'us' => 219, 'bd' => 21000, 'cx' => 'standard', 'eta' => 'Same–next business day',
                'short' => 'Diagnose and fix connectivity and network faults.',
                'desc' => 'Systematic network troubleshooting covering connectivity loss, latency, packet loss, routing, switching and DNS issues, with root cause documentation.',
                'feat' => ['Fault diagnosis', 'Packet-level analysis', 'Fix documentation'], 'tags' => ['network', 'troubleshooting', 'connectivity']],
            ['name' => 'Wi-Fi, LAN & WAN Management', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 599, 'us' => 719, 'bd' => 65000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Design, manage and optimize wireless, LAN and WAN networks.',
                'desc' => 'Management of Wi-Fi, LAN and WAN infrastructure covering design, access point placement, VLANs, routing, WAN links and ongoing optimization.',
                'feat' => ['Wi-Fi design', 'VLAN and routing', 'WAN link management'], 'tags' => ['wifi', 'lan', 'wan']],
            ['name' => 'DNS, DHCP & Directory Services', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 449, 'us' => 549, 'bd' => 50000, 'cx' => 'advanced', 'eta' => '1–2 weeks',
                'short' => 'Configure and manage DNS, DHCP and directory services.',
                'desc' => 'Setup, migration and management of DNS zones, DHCP scopes and directory services with redundancy, documentation and change control.',
                'feat' => ['DNS zone management', 'DHCP scopes', 'Redundancy setup'], 'tags' => ['dns', 'dhcp', 'directory']],
            ['name' => 'Infrastructure Documentation', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 399, 'us' => 479, 'bd' => 45000, 'cx' => 'standard', 'eta' => '1–3 weeks',
                'short' => 'Document servers, network and infrastructure as-built.',
                'desc' => 'We produce clear infrastructure documentation: topology diagrams, server inventories, IP addressing, credentials index (secured) and configuration records.',
                'feat' => ['Topology diagrams', 'Inventory records', 'Configuration docs'], 'tags' => ['documentation', 'infrastructure', 'diagrams']],
            ['name' => 'AWS Administration', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 749, 'us' => 899, 'bd' => 85000, 'cx' => 'advanced', 'eta' => 'Ongoing / per task',
                'short' => 'Day-to-day administration of AWS accounts and workloads.',
                'desc' => 'AWS administration covering account and IAM management, EC2/RDS operations, networking, monitoring, cost hygiene and security best practice.',
                'feat' => ['IAM and account management', 'Compute and database ops', 'Monitoring and cost hygiene'], 'tags' => ['aws', 'cloud', 'administration']],
            ['name' => 'Azure Administration', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 749, 'us' => 899, 'bd' => 85000, 'cx' => 'advanced', 'eta' => 'Ongoing / per task',
                'short' => 'Administration of Microsoft Azure subscriptions and resources.',
                'desc' => 'Azure administration covering subscriptions, resource groups, VMs, networking, storage, monitoring and cost management with security baselines.',
                'feat' => ['Resource and VM management', 'Networking and storage', 'Monitoring and cost control'], 'tags' => ['azure', 'cloud', 'administration']],
            ['name' => 'Google Cloud Fundamentals', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 649, 'us' => 779, 'bd' => 70000, 'cx' => 'standard', 'eta' => '1–2 weeks',
                'short' => 'Set up and manage foundational Google Cloud workloads.',
                'desc' => 'Get started on Google Cloud: projects, IAM, compute, storage, networking and billing setup, with guidance on best practice for small deployments.',
                'feat' => ['Project and IAM setup', 'Compute and storage', 'Billing guidance'], 'tags' => ['gcp', 'google-cloud', 'cloud']],
            ['name' => 'Cloud Cost Optimization', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 599, 'us' => 719, 'bd' => 65000, 'cx' => 'advanced', 'eta' => '2–3 weeks',
                'short' => 'Reduce cloud spend without hurting performance or reliability.',
                'desc' => 'Cloud cost optimization covering usage analysis, right-sizing, reserved/committed capacity, storage tiering and waste elimination across AWS, Azure and GCP.',
                'feat' => ['Spend analysis', 'Right-sizing', 'Savings roadmap'], 'tags' => ['cloud', 'cost', 'finops']],
            ['name' => 'Monitoring, Logging & Observability', 'cat' => 'cloud-server-network', 'pt' => 'starting_from', 'uk' => 699, 'us' => 839, 'bd' => 78000, 'cx' => 'advanced', 'eta' => '2–3 weeks',
                'short' => 'Centralized monitoring, logging and observability for your estate.',
                'desc' => 'Design and deploy monitoring, centralized logging and observability with dashboards, thresholds and alerting across servers, network and applications.',
                'feat' => ['Metrics and dashboards', 'Centralized logging', 'Alerting thresholds'], 'tags' => ['monitoring', 'logging', 'observability']],

            // ── Microsoft 365 & Business IT ────────────────────────────────────
            ['name' => 'Active Directory & Group Policy', 'cat' => 'microsoft-365-business-it', 'pt' => 'starting_from', 'uk' => 599, 'us' => 719, 'bd' => 65000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Design, manage and troubleshoot AD and Group Policy.',
                'desc' => 'Active Directory and Group Policy services covering user/computer lifecycle, OU design, GPO management, replication health and hybrid identity alignment.',
                'feat' => ['OU and GPO design', 'User lifecycle', 'Replication health'], 'tags' => ['active-directory', 'group-policy', 'identity']],
            ['name' => 'Identity, Access & Permissions Management', 'cat' => 'microsoft-365-business-it', 'pt' => 'starting_from', 'uk' => 499, 'us' => 599, 'bd' => 55000, 'cx' => 'advanced', 'eta' => '1–2 weeks',
                'short' => 'Manage identities, roles and access permissions safely.',
                'desc' => 'Identity and access management covering role design, least-privilege reviews, access requests, MFA and periodic permission recertification.',
                'feat' => ['Least-privilege review', 'Access requests', 'MFA and recertification'], 'tags' => ['identity', 'access', 'permissions']],
            ['name' => 'Exchange Online & Email Support', 'cat' => 'microsoft-365-business-it', 'pt' => 'starting_from', 'uk' => 349, 'us' => 419, 'bd' => 40000, 'cx' => 'standard', 'eta' => 'Ongoing / per task',
                'short' => 'Exchange Online mailbox and mail-flow support.',
                'desc' => 'Exchange Online support covering mailbox setup, permissions, shared mailboxes, mail-flow rules, delivery troubleshooting and hygiene settings.',
                'feat' => ['Mailbox and permissions', 'Mail-flow troubleshooting', 'Anti-spam hygiene'], 'tags' => ['exchange-online', 'email', 'm365']],
            ['name' => 'Business Email Delivery & DNS Support', 'cat' => 'microsoft-365-business-it', 'pt' => 'fixed', 'uk' => 249, 'us' => 299, 'bd' => 29500, 'cx' => 'standard', 'eta' => '3–7 business days',
                'short' => 'Fix email deliverability with correct DNS records.',
                'desc' => 'Ensure business email lands in inboxes: correct SPF, DKIM, DMARC, MX and related DNS records, with testing and monitoring for deliverability.',
                'feat' => ['SPF/DKIM/DMARC setup', 'MX and DNS records', 'Deliverability testing'], 'tags' => ['email', 'dns', 'deliverability']],

            // ── Cybersecurity ──────────────────────────────────────────────────
            ['name' => 'Server Hardening', 'cat' => 'cybersecurity', 'pt' => 'starting_from', 'uk' => 649, 'us' => 779, 'bd' => 70000, 'cx' => 'advanced', 'eta' => '1–2 weeks',
                'short' => 'Harden servers against attack with secure baselines.',
                'desc' => 'Server hardening against CIS-style baselines: service and feature reduction, patch and account controls, secure configuration and audit logging.',
                'feat' => ['Baseline review', 'Secure configuration', 'Audit logging'], 'tags' => ['hardening', 'server', 'baseline']],
            ['name' => 'Endpoint Detection & Response (EDR)', 'cat' => 'cybersecurity', 'pt' => 'starting_from', 'uk' => 899, 'us' => 1079, 'bd' => 100000, 'cx' => 'advanced', 'eta' => '2–3 weeks',
                'short' => 'Deploy and tune EDR across your endpoints.',
                'desc' => 'Endpoint detection and response deployment covering agent rollout, policy tuning, threat hunting, alert triage and response runbooks across Windows, macOS and Linux.',
                'feat' => ['EDR agent rollout', 'Policy tuning', 'Alert and response runbooks'], 'tags' => ['edr', 'endpoint', 'detection']],
            ['name' => 'SIEM Monitoring & Alert Triage', 'cat' => 'cybersecurity', 'pt' => 'starting_from', 'uk' => 1299, 'us' => 1559, 'bd' => 145000, 'cx' => 'enterprise', 'eta' => '2–4 weeks',
                'short' => 'Centralize security logs and triage alerts.',
                'desc' => 'SIEM deployment and tuning covering log source integration, detection rules, dashboards and alert triage workflows with escalation guidance.',
                'feat' => ['Log source integration', 'Detection rules', 'Alert triage workflow'], 'tags' => ['siem', 'monitoring', 'triage']],
            ['name' => 'Identity Threat Detection', 'cat' => 'cybersecurity', 'pt' => 'starting_from', 'uk' => 799, 'us' => 959, 'bd' => 90000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Detect risky sign-ins and identity-based attacks.',
                'desc' => 'Identity threat detection covering risky sign-in analysis, impossible-travel and brute-force detection, privilege abuse monitoring and MFA enforcement.',
                'feat' => ['Risky sign-in detection', 'Privilege abuse monitoring', 'MFA enforcement'], 'tags' => ['identity', 'threat-detection', 'security']],
            ['name' => 'Security Logging & Audit Readiness', 'cat' => 'cybersecurity', 'pt' => 'starting_from', 'uk' => 599, 'us' => 719, 'bd' => 65000, 'cx' => 'standard', 'eta' => '1–2 weeks',
                'short' => 'Retain the right security logs and be audit-ready.',
                'desc' => 'Configure security logging, retention and access so you can evidence activity and meet audit or compliance requirements.',
                'feat' => ['Log source review', 'Retention policy', 'Audit-ready evidence'], 'tags' => ['logging', 'audit', 'compliance']],

            // ── DevOps, Scripting & Automation ─────────────────────────────────
            ['name' => 'CI/CD & DevOps Automation', 'cat' => 'devops-automation', 'pt' => 'starting_from', 'uk' => 1299, 'us' => 1559, 'bd' => 145000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Build automated build, test and deployment pipelines.',
                'desc' => 'We design and implement CI/CD pipelines with automated testing, artifact management, environment promotion and safe deployment strategies.',
                'feat' => ['Pipeline design', 'Automated testing', 'Deployment automation'], 'tags' => ['cicd', 'devops', 'automation']],
            ['name' => 'Linux Web Server & Reverse Proxy', 'cat' => 'devops-automation', 'pt' => 'starting_from', 'uk' => 599, 'us' => 719, 'bd' => 65000, 'cx' => 'advanced', 'eta' => '1–2 weeks',
                'short' => 'Configure Nginx/Apache, TLS and reverse proxy setups.',
                'desc' => 'Linux web server setup and tuning with Nginx or Apache, TLS termination, reverse proxy, virtual hosts, caching and security hardening.',
                'feat' => ['Nginx/Apache setup', 'TLS and reverse proxy', 'Performance tuning'], 'tags' => ['linux', 'nginx', 'reverse-proxy']],
            ['name' => 'Infrastructure as Code (IaC)', 'cat' => 'devops-automation', 'pt' => 'starting_from', 'uk' => 1099, 'us' => 1319, 'bd' => 120000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Provision infrastructure with Terraform and IaC.',
                'desc' => 'Define your infrastructure as code with Terraform or similar: reusable modules, environments, state management and change review.',
                'feat' => ['Terraform modules', 'Environment parity', 'State management'], 'tags' => ['iac', 'terraform', 'devops']],
            ['name' => 'Git & Source Control', 'cat' => 'devops-automation', 'pt' => 'fixed', 'uk' => 399, 'us' => 479, 'bd' => 45000, 'cx' => 'standard', 'eta' => '3–7 business days',
                'short' => 'Set up Git workflows, branching and code review.',
                'desc' => 'Git and source control setup covering repositories, branching strategy, pull-request review, hooks and access control.',
                'feat' => ['Repo and branching strategy', 'Code review workflow', 'Access control'], 'tags' => ['git', 'source-control', 'workflow']],
            ['name' => 'Secure Software Development', 'cat' => 'devops-automation', 'pt' => 'starting_from', 'uk' => 899, 'us' => 1079, 'bd' => 100000, 'cx' => 'advanced', 'eta' => '2–3 weeks',
                'short' => 'Bake security into your development lifecycle.',
                'desc' => 'Secure SDLC guidance covering threat modeling, secure coding standards, dependency scanning, secret management and security reviews in the pipeline.',
                'feat' => ['Threat modeling', 'Secure coding standards', 'Pipeline security checks'], 'tags' => ['ssdlc', 'secure-development', 'security']],
            ['name' => 'Website Testing & Quality Assurance', 'cat' => 'devops-automation', 'pt' => 'starting_from', 'uk' => 599, 'us' => 719, 'bd' => 65000, 'cx' => 'standard', 'eta' => '1–2 weeks',
                'short' => 'Functional, cross-browser and regression testing for websites.',
                'desc' => 'Website QA covering functional testing, cross-browser and device checks, regression suites and bug reporting ahead of releases.',
                'feat' => ['Functional testing', 'Cross-browser checks', 'Regression suites'], 'tags' => ['qa', 'testing', 'website']],
            ['name' => 'PowerShell IT Automation', 'cat' => 'devops-automation', 'pt' => 'hourly', 'uk' => 90, 'us' => 110, 'bd' => 5500, 'cx' => 'standard', 'eta' => 'Per task',
                'short' => 'Automate Windows and Microsoft 365 tasks with PowerShell.',
                'desc' => 'PowerShell scripting and automation for onboarding, reporting, bulk changes and routine Windows/Microsoft 365 administration tasks.',
                'feat' => ['Admin automation scripts', 'Bulk and reporting tasks', 'Scheduled execution'], 'tags' => ['powershell', 'automation', 'windows']],
            ['name' => 'Python IT Automation', 'cat' => 'devops-automation', 'pt' => 'hourly', 'uk' => 90, 'us' => 110, 'bd' => 5500, 'cx' => 'standard', 'eta' => 'Per task',
                'short' => 'Automate IT and data tasks with Python.',
                'desc' => 'Python scripting for IT automation: data processing, API integrations, file and log handling, and scheduled operational tasks.',
                'feat' => ['Automation scripts', 'API and data tasks', 'Scheduling'], 'tags' => ['python', 'automation', 'scripting']],
            ['name' => 'Bash & Linux Automation', 'cat' => 'devops-automation', 'pt' => 'hourly', 'uk' => 85, 'us' => 105, 'bd' => 5000, 'cx' => 'standard', 'eta' => 'Per task',
                'short' => 'Automate Linux operations with Bash and cron.',
                'desc' => 'Bash scripting and Linux automation covering backups, health checks, log rotation, cron jobs and deployment helper scripts.',
                'feat' => ['Bash scripts', 'Cron scheduling', 'Ops automation'], 'tags' => ['bash', 'linux', 'automation']],
            ['name' => 'Windows Task & Job Automation', 'cat' => 'devops-automation', 'pt' => 'fixed', 'uk' => 299, 'us' => 359, 'bd' => 35000, 'cx' => 'standard', 'eta' => '3–7 business days',
                'short' => 'Automate recurring Windows tasks and scheduled jobs.',
                'desc' => 'Automate recurring Windows work with Task Scheduler and scripts: report generation, file movement, cleanup and health checks.',
                'feat' => ['Scheduled jobs', 'Report automation', 'Cleanup routines'], 'tags' => ['windows', 'task-scheduler', 'automation']],
            ['name' => 'Log Analysis & Automated Diagnostics', 'cat' => 'devops-automation', 'pt' => 'starting_from', 'uk' => 499, 'us' => 599, 'bd' => 55000, 'cx' => 'advanced', 'eta' => '1–2 weeks',
                'short' => 'Analyze logs and build automated diagnostics.',
                'desc' => 'We analyze application and system logs, identify error patterns, and build automated diagnostics and alerts for common failure signatures.',
                'feat' => ['Log pattern analysis', 'Error correlation', 'Automated diagnostics'], 'tags' => ['logs', 'analysis', 'diagnostics']],
            ['name' => 'Script Testing & Code Review', 'cat' => 'devops-automation', 'pt' => 'hourly', 'uk' => 95, 'us' => 115, 'bd' => 6000, 'cx' => 'standard', 'eta' => 'Per task',
                'short' => 'Review and test scripts before they run in production.',
                'desc' => 'Independent review and testing of automation scripts — safety checks, error handling, idempotency and dry-run validation before production use.',
                'feat' => ['Script review', 'Safety and error handling', 'Dry-run validation'], 'tags' => ['code-review', 'testing', 'scripts']],
            ['name' => 'Secure Secrets & Configuration Management', 'cat' => 'devops-automation', 'pt' => 'starting_from', 'uk' => 799, 'us' => 959, 'bd' => 90000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Manage secrets and configuration securely.',
                'desc' => 'Implement secure management of API keys, passwords and configuration using secret stores and environment separation, with rotation and access controls.',
                'feat' => ['Secret store setup', 'Rotation policy', 'Environment separation'], 'tags' => ['secrets', 'config', 'security']],

            // ── AI Agents & LLM Engineering ────────────────────────────────────
            ['name' => 'AI Agent Architecture & Design', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1499, 'us' => 1799, 'bd' => 165000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Design the architecture for a reliable AI agent.',
                'desc' => 'We design AI agent architectures: goals, tools, memory, guardrails, model choice and evaluation approach, producing a build-ready specification.',
                'feat' => ['Agent design spec', 'Tool and memory design', 'Guardrail strategy'], 'tags' => ['ai-agent', 'architecture', 'design']],
            ['name' => 'AI Agent Development', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 2499, 'us' => 2999, 'bd' => 275000, 'cx' => 'advanced', 'eta' => '3–6 weeks',
                'short' => 'Build custom AI agents with tools and integrations.',
                'desc' => 'Custom AI agent development with tool use, API integrations, retrieval and human-in-the-loop approval flows, delivered with tests and documentation.',
                'feat' => ['Tool-enabled agent build', 'API integrations', 'Human-in-the-loop controls'], 'tags' => ['ai-agent', 'development', 'llm']],
            ['name' => 'AI Agent Deployment & Configuration', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 999, 'us' => 1199, 'bd' => 110000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Deploy and configure AI agents in your environment.',
                'desc' => 'Deployment and configuration of AI agents: hosting, environment configuration, secrets, scaling, logging and integration with your systems.',
                'feat' => ['Hosting and scaling', 'Environment config', 'Logging and integration'], 'tags' => ['ai-agent', 'deployment', 'configuration']],
            ['name' => 'AI Agent Testing & Evaluation', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 899, 'us' => 1079, 'bd' => 100000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Test and evaluate agent quality and safety.',
                'desc' => 'We build evaluation suites for AI agents — test sets, scoring, regression checks and safety testing — to measure quality before and after changes.',
                'feat' => ['Eval test sets', 'Scoring rubric', 'Regression checks'], 'tags' => ['ai-agent', 'evaluation', 'testing']],
            ['name' => 'AI Agent Monitoring & Maintenance', 'cat' => 'ai-agents', 'pt' => 'recurring', 'uk' => 299, 'us' => 359, 'bd' => 35000, 'cx' => 'standard', 'eta' => 'Monthly retainer',
                'short' => 'Ongoing monitoring, tuning and maintenance of live agents.',
                'desc' => 'Monthly monitoring and maintenance of production AI agents: usage and error monitoring, prompt and cost tuning, and incremental improvements.',
                'feat' => ['Usage and error monitoring', 'Prompt and cost tuning', 'Incremental improvements'], 'tags' => ['ai-agent', 'monitoring', 'maintenance']],
            ['name' => 'Claude Desktop Configuration', 'cat' => 'ai-agents', 'pt' => 'fixed', 'uk' => 249, 'us' => 299, 'bd' => 29500, 'cx' => 'standard', 'eta' => '2–5 business days',
                'short' => 'Configure Claude Desktop with MCP servers and projects.',
                'desc' => 'Set up and configure Claude Desktop for your team: desktop clients, MCP server connections, project setup and usage guidance for safe adoption.',
                'feat' => ['Desktop client setup', 'MCP connections', 'Usage guidance'], 'tags' => ['claude', 'desktop', 'mcp']],
            ['name' => 'Claude Skills Creation & Maintenance', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 499, 'us' => 599, 'bd' => 55000, 'cx' => 'standard', 'eta' => '1–2 weeks',
                'short' => 'Create and maintain Claude skills for your workflows.',
                'desc' => 'We create, test and maintain Claude skills that package your instructions, tools and resources so the assistant performs consistent, repeatable tasks.',
                'feat' => ['Skill authoring', 'Testing and versioning', 'Maintenance'], 'tags' => ['claude', 'skills', 'ai']],
            ['name' => 'Claude MCP Server Configuration', 'cat' => 'ai-agents', 'pt' => 'fixed', 'uk' => 499, 'us' => 599, 'bd' => 55000, 'cx' => 'standard', 'eta' => '3–7 business days',
                'short' => 'Connect Claude to your tools via MCP servers.',
                'desc' => 'Configure Model Context Protocol servers to connect Claude to your files, databases and business apps securely, with permissions and testing.',
                'feat' => ['MCP server setup', 'Secure connections', 'Permission scoping'], 'tags' => ['claude', 'mcp', 'integration']],
            ['name' => 'MCP Server Development & Debugging', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1299, 'us' => 1559, 'bd' => 145000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Build and debug custom MCP servers.',
                'desc' => 'Custom Model Context Protocol server development and debugging: tools, resources and prompts exposed to AI clients safely, with tests and docs.',
                'feat' => ['Custom MCP server build', 'Tool and resource design', 'Debugging and tests'], 'tags' => ['mcp', 'development', 'ai']],
            ['name' => 'RAG & Knowledge Base Development', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1499, 'us' => 1799, 'bd' => 165000, 'cx' => 'advanced', 'eta' => '3–5 weeks',
                'short' => 'Build retrieval-augmented assistants over your knowledge.',
                'desc' => 'Retrieval-augmented generation (RAG) systems that answer from your documents: ingestion, chunking, retrieval quality tuning and grounded responses.',
                'feat' => ['Document ingestion', 'Retrieval tuning', 'Grounded responses'], 'tags' => ['rag', 'knowledge-base', 'llm']],
            ['name' => 'Vector Search & Embeddings', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1099, 'us' => 1319, 'bd' => 120000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Implement vector search and embeddings pipelines.',
                'desc' => 'Design and implement embedding and vector search infrastructure: model choice, indexing, hybrid search and relevance evaluation.',
                'feat' => ['Embedding pipeline', 'Vector index setup', 'Relevance evaluation'], 'tags' => ['vector-search', 'embeddings', 'ai']],
            ['name' => 'Customer Support AI Agent', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1999, 'us' => 2399, 'bd' => 220000, 'cx' => 'advanced', 'eta' => '3–6 weeks',
                'short' => 'AI agent that handles customer support conversations.',
                'desc' => 'A customer-support AI agent grounded in your knowledge base, handling FAQs and requests with escalation to humans and audit logging.',
                'feat' => ['Knowledge-grounded answers', 'Human escalation', 'Conversation logging'], 'tags' => ['ai-agent', 'customer-support', 'chatbot']],
            ['name' => 'IT Helpdesk AI Agent', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1999, 'us' => 2399, 'bd' => 220000, 'cx' => 'advanced', 'eta' => '3–6 weeks',
                'short' => 'AI agent for IT helpdesk triage and first-line responses.',
                'desc' => 'An IT helpdesk AI agent that triages requests, suggests first-line fixes from your KB and creates or routes tickets to the right team.',
                'feat' => ['Ticket triage', 'KB-driven fixes', 'Ticket creation and routing'], 'tags' => ['ai-agent', 'helpdesk', 'itsm']],
            ['name' => 'Multi-Agent Orchestration', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 2499, 'us' => 2999, 'bd' => 275000, 'cx' => 'enterprise', 'eta' => '4–8 weeks',
                'short' => 'Coordinate multiple AI agents on complex workflows.',
                'desc' => 'Design and build multi-agent systems where specialized agents collaborate under an orchestrator with shared state and reliable handoffs.',
                'feat' => ['Orchestrator design', 'Agent collaboration', 'Reliable handoffs'], 'tags' => ['multi-agent', 'orchestration', 'ai']],
            ['name' => 'AI Tool Use & Permissions', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 899, 'us' => 1079, 'bd' => 100000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Give agents tools safely with scoped permissions.',
                'desc' => 'We implement tool use for AI agents with least-privilege permissions, approval gates and audit trails so actions stay safe and accountable.',
                'feat' => ['Scoped tool permissions', 'Approval gates', 'Audit trails'], 'tags' => ['ai-agent', 'tool-use', 'permissions']],
            ['name' => 'AI Agent Security & Prompt Injection Defense', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1499, 'us' => 1799, 'bd' => 165000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Defend AI agents against prompt injection and abuse.',
                'desc' => 'Security hardening for AI agents: prompt-injection defenses, input/output filtering, permission boundaries, rate limits and monitoring.',
                'feat' => ['Injection defenses', 'Input/output filtering', 'Rate limits and monitoring'], 'tags' => ['ai-security', 'prompt-injection', 'ai']],
            ['name' => 'AI Agent Evaluation & Observability', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1099, 'us' => 1319, 'bd' => 120000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Observe and measure agent behaviour in production.',
                'desc' => 'Instrument AI agents with tracing, metrics and evaluation so you can see behaviour, catch regressions and prove quality over time.',
                'feat' => ['Tracing and metrics', 'Evaluation harness', 'Regression detection'], 'tags' => ['observability', 'evaluation', 'ai']],
            ['name' => 'Local AI Model Deployment', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 1299, 'us' => 1559, 'bd' => 145000, 'cx' => 'advanced', 'eta' => '2–4 weeks',
                'short' => 'Run open AI models locally for privacy and control.',
                'desc' => 'Deploy open-weight models on your own hardware or private cloud (e.g. Ollama), covering model selection, GPU sizing, serving and integration.',
                'feat' => ['Model selection', 'GPU and serving setup', 'Private integration'], 'tags' => ['local-ai', 'ollama', 'privacy']],
            ['name' => 'AI Cost, Latency & Reliability Optimization', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 999, 'us' => 1199, 'bd' => 110000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Reduce AI cost and latency while improving reliability.',
                'desc' => 'Optimize AI workloads for cost, speed and reliability: model routing, caching, batching, fallback strategies and token efficiency.',
                'feat' => ['Model routing', 'Caching and batching', 'Fallback strategies'], 'tags' => ['ai', 'optimization', 'reliability']],
            ['name' => 'AI Agent Documentation & Handover', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 699, 'us' => 839, 'bd' => 78000, 'cx' => 'standard', 'eta' => '1–3 weeks',
                'short' => 'Document AI agents for safe operation and handover.',
                'desc' => 'We produce AI agent documentation: architecture, prompts, tools, runbooks, limitations and handover guidance so your team can operate it confidently.',
                'feat' => ['Architecture docs', 'Runbooks', 'Handover guidance'], 'tags' => ['ai-agent', 'documentation', 'handover']],
            ['name' => 'LLM API Integration', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 899, 'us' => 1079, 'bd' => 100000, 'cx' => 'standard', 'eta' => '1–3 weeks',
                'short' => 'Integrate LLM APIs into your products and workflows.',
                'desc' => 'Integration of large language model APIs (OpenAI, Anthropic, OpenRouter and others) into your apps with streaming, error handling and cost controls.',
                'feat' => ['Provider integration', 'Streaming and errors', 'Cost controls'], 'tags' => ['llm', 'api', 'integration']],
            ['name' => 'AI Workflow Automation', 'cat' => 'ai-agents', 'pt' => 'starting_from', 'uk' => 999, 'us' => 1199, 'bd' => 110000, 'cx' => 'standard', 'eta' => '1–3 weeks',
                'short' => 'Add AI steps to your automated business workflows.',
                'desc' => 'We embed AI steps into workflows — summarization, classification, drafting and routing — connected to your existing tools and triggers.',
                'feat' => ['AI workflow steps', 'Tool integrations', 'Trigger-based automation'], 'tags' => ['ai', 'workflow', 'automation']],

            // ── Data & Business Intelligence ───────────────────────────────────
            ['name' => 'Data Processing & CSV Automation', 'cat' => 'data-business-intelligence', 'pt' => 'starting_from', 'uk' => 399, 'us' => 479, 'bd' => 45000, 'cx' => 'standard', 'eta' => '3–7 business days',
                'short' => 'Automate CSV and spreadsheet processing tasks.',
                'desc' => 'Automate recurring data processing: CSV import/transform/export, validation, deduplication and scheduled batch jobs with error reporting.',
                'feat' => ['CSV import/export', 'Transformation rules', 'Scheduled processing'], 'tags' => ['csv', 'data', 'automation']],
            ['name' => 'Automated Reporting', 'cat' => 'data-business-intelligence', 'pt' => 'starting_from', 'uk' => 549, 'us' => 659, 'bd' => 60000, 'cx' => 'standard', 'eta' => '1–2 weeks',
                'short' => 'Scheduled reports delivered automatically to stakeholders.',
                'desc' => 'Build automated reporting that pulls from your systems and delivers scheduled PDF, Excel or email reports to the right people.',
                'feat' => ['Scheduled reports', 'Multi-format output', 'Distribution lists'], 'tags' => ['reporting', 'automation', 'data']],

            // ── Web Development ────────────────────────────────────────────────
            ['name' => 'API Integration & Webhooks', 'cat' => 'web-development', 'pt' => 'starting_from', 'uk' => 899, 'us' => 1079, 'bd' => 100000, 'cx' => 'advanced', 'eta' => '1–3 weeks',
                'short' => 'Connect systems with APIs and webhooks.',
                'desc' => 'Integration work covering third-party APIs and webhooks: authentication, data mapping, error handling, retries and event-driven automation.',
                'feat' => ['API and webhook integration', 'Auth and data mapping', 'Retries and error handling'], 'tags' => ['api', 'webhooks', 'integration']],

            // ── E-commerce ─────────────────────────────────────────────────────
            ['name' => 'E-commerce Troubleshooting & Performance', 'cat' => 'ecommerce-online-business', 'pt' => 'starting_from', 'uk' => 499, 'us' => 599, 'bd' => 55000, 'cx' => 'standard', 'eta' => '3–7 business days',
                'short' => 'Diagnose and fix slow or broken online stores.',
                'desc' => 'Troubleshoot and optimize online stores: checkout errors, payment failures, slow pages, plugin conflicts and caching, with performance fixes.',
                'feat' => ['Checkout and payment fixes', 'Speed optimization', 'Plugin conflict resolution'], 'tags' => ['ecommerce', 'performance', 'troubleshooting']],

            // ── IT Training & Consulting ───────────────────────────────────────
            ['name' => 'IT Training & User Documentation', 'cat' => 'it-training-consulting', 'pt' => 'starting_from', 'uk' => 599, 'us' => 719, 'bd' => 65000, 'cx' => 'standard', 'eta' => '1–3 weeks',
                'short' => 'Train users and produce clear how-to documentation.',
                'desc' => 'User training sessions plus written user guides and quick-reference documentation so staff adopt systems confidently and support load drops.',
                'feat' => ['Live or recorded training', 'User guides', 'Quick-reference sheets'], 'tags' => ['training', 'user-documentation', 'enablement']],
        ];

        $created = 0;

        foreach ($services as $i => $s) {
            $category = ServiceCategory::where('slug', $s['cat'])->first();
            if (! $category) {
                $this->command?->warn("Category {$s['cat']} missing — skipping {$s['name']}.");

                continue;
            }

            $slug = Str::slug($s['name']);

            $service = Service::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $s['name'],
                    'short_description' => $s['short'],
                    'description' => $s['desc'],
                    'full_description' => $s['desc'],
                    'price_type' => $s['pt'],
                    'complexity_level' => $s['cx'],
                    'estimated_completion' => $s['eta'],
                    'starting_price' => $s['us'],
                    'allows_custom_quote' => true,
                    'is_featured' => false,
                    'is_active' => true,
                    'is_demo' => false,
                    'sort_order' => 100 + $i,
                    'features' => $s['feat'],
                    'deliverables' => $s['feat'],
                    'tags' => $s['tags'],
                ]
            );

            if ($service->wasRecentlyCreated) {
                $created++;
            }

            foreach (['UK' => $s['uk'], 'US' => $s['us'], 'BD' => $s['bd']] as $code => $price) {
                ServiceCountryPrice::firstOrCreate(
                    ['service_id' => $service->id, 'country_id' => $countryIds[$code]],
                    ['pricing_type' => $s['pt'], 'price' => $price, 'is_active' => true]
                );
            }
        }

        $this->command?->info("Extended IT services ready: {$created} new service(s) created this run (idempotent — re-runs add nothing).");
    }
}
