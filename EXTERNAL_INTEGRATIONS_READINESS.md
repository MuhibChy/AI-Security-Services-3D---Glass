# External Integration Readiness Assessment

**Date:** 2026-10-31  
**Target:** AI Security Services 3D Glass Website & AI Support Agent  
**Security Policy:** Never expose API keys, secrets, credentials, or tokens in source code, logs, reports, or frontend responses. Do not connect to external customer systems without explicit authorization.

---

## 1. Remote Monitoring and Management (RMM) Integration

| Attribute | Specification |
|---|---|
| **Required API** | REST API (e.g., Datto RMM, Zabbix, or NinjaOne API) |
| **Required Credentials** | API Base URL, Client Key, Secret / OAuth2 Bearer Token (Stored in `.env` as `RMM_API_URL`, `RMM_API_KEY`) |
| **Required Permissions** | Read-only endpoint telemetry, device status monitoring, alert polling. (Write permissions only for approved automated remediation scripts). |
| **Connection Configuration** | Configured via Laravel service provider (`RmmServiceProvider`) and environment variables. |
| **Available Functionality** | Live server and endpoint telemetry, CPU/disk metrics, systemd service status, event viewer warnings. |
| **Security Requirements** | TLS 1.3 encryption in transit, strict token rotation, least-privilege API roles, audit logging for all automated commands. |
| **Testing Procedure** | Run mock API integration tests using recorded responses (`php artisan test --filter=RmmTest`). |
| **Current Connection Status** | **Requires External Integration** (Pending client API credentials in production `.env`). |

---

## 2. Microsoft Azure / Entra ID Integration

| Attribute | Specification |
|---|---|
| **Required API** | Microsoft Graph API (`https://graph.microsoft.com`) & Azure Resource Manager API |
| **Required Credentials** | Azure App Registration Client ID, Tenant ID, and Client Secret / Certificate (Stored in `.env`) |
| **Required Permissions** | `User.Read.All`, `Directory.Read.All`, `AuditLog.Read.All`, `DeviceManagementManagedDevices.Read.All` |
| **Connection Configuration** | Laravel Microsoft Graph client wrapper initialized via configuration file (`config/azure.php`). |
| **Available Functionality** | User synchronization, Entra ID authentication status, Intune device compliance reporting, Exchange Online mail flow inspection. |
| **Security Requirements** | Azure Key Vault secret management, strict application permission scoping (no Global Administrator keys stored), IP restriction rules. |
| **Testing Procedure** | Test graph queries against a staging tenant sandbox. |
| **Current Connection Status** | **Requires External Integration** (Pending Azure app registration and tenant consent). |

---

## 3. Amazon Web Services (AWS) Integration

| Attribute | Specification |
|---|---|
| **Required API** | AWS SDK for PHP / CloudWatch & EC2 APIs |
| **Required Credentials** | IAM Role / Access Key ID & Secret Access Key (Stored securely via AWS Secrets Manager or `.env`) |
| **Required Permissions** | `cloudwatch:GetMetricData`, `ec2:DescribeInstances`, `ec2:RebootInstances` (restricted by IAM policy) |
| **Connection Configuration** | AWS SDK client configured via region (`AWS_DEFAULT_REGION`) and credentials in `.env`. |
| **Available Functionality** | CloudWatch CPU utilization metrics, instance health status, safe EC2 reboot operations (under strict Amber/Red approval policy). |
| **Security Requirements** | IAM least-privilege policies, MFA enforcement on AWS root accounts, CloudTrail auditing. |
| **Testing Procedure** | Validate IAM policy simulation and test metric retrieval against a test AWS account. |
| **Current Connection Status** | **Requires External Integration** (Pending AWS IAM credentials). |

---

## 4. Ticketing, Notification, and Monitoring Services

| Integration Name | API / Protocol | Required Credentials | Status | Notes |
|---|---|---|---|---|
| **Website Portal Ticketing** | Laravel Eloquent / MySQL | Database Connection (`DB_DATABASE`, etc.) | **Operational** | Fully integrated with SLA service and immutable audit logging. |
| **Email Notifications** | SMTP / Mail Drivers | SMTP Host, Port, Username, Password (`MAIL_*`) | **Operational** | Configurable via `.env`. Sends customer ticket updates and escalation alerts. |
| **Knowledge Base RAG Search** | Laravel Eloquent Search | Database Connection | **Operational** | Stop-word filtering, role-visibility checks, and untrusted data boundaries. |
| **Application Error Monitoring** | Sentry / Error Logger | Sentry DSN (`SENTRY_LARAVEL_DSN`) | **Operational / Configurable** | Captures unhandled server exceptions and runtime errors. |
