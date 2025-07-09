# Changelog n8n Connect

## 0.1.0
- First version of the n8n Connect plugin for Jeedom.
  This plugin allows you to control and monitor your n8n workflows directly from your Jeedom home automation interface. It offers simple and effective integration to launch workflows, activate/deactivate them, and check their status.

  Features included:
  - n8n instance configuration: Easily connect your Jeedom to your n8n instance via its URL and an API key.
  - Workflow management: Create Jeedom equipment for each n8n workflow you want to control.
  - Action commands:
    - Activate/Deactivate: Change the execution status of your n8n workflows.
    - Launch (via Webhook): Trigger an n8n workflow by sending a request to its configured webhook URL.
  - Information command:
    - Status: Get the status (active/inactive) of your n8n workflow.
  - Workflow error notifications: Receive notifications in Jeedom when an n8n workflow fails.
  - Simplified selection: Choose your n8n workflows via a dropdown list or manually enter their ID.
  - Detailed logging: Precise logs to facilitate diagnosis in case of problems.

## 0.1.1
- Corrected Italian description in info.json.
- Fixed JSON syntax error in info.json (extra comma).
