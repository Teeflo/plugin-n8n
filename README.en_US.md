# n8n Connect for Jeedom

This plugin allows you to control and monitor your **n8n** workflows directly from your Jeedom home automation interface. It offers simple and effective integration to launch workflows, activate/deactivate them, and check their status.

## Features

*   **n8n instance configuration:** Easily connect your Jeedom to your n8n instance via its URL and an API key.
*   **Workflow management:** Create Jeedom equipment for each n8n workflow you want to control.
*   **Action commands:**
    *   **Activate/Deactivate:** Change the execution status of your n8n workflows.
    *   **Launch (via Webhook):** Trigger an n8n workflow by sending a request to its configured webhook URL. This command only appears if a webhook is configured for the equipment.
*   **Information command:**
    *   **Status:** Get the status (active/inactive) of your n8n workflow.
*   **Workflow error notifications:** Receive notifications in Jeedom when an n8n workflow fails, allowing proactive problem management.
*   **Simplified selection:** Choose your n8n workflows via a dropdown list or manually enter their ID.
*   **Detailed logging:** Precise logs to facilitate diagnosis in case of problems.

## Prerequisites

1.  A functional n8n instance accessible from your Jeedom.
2.  The n8n REST API must be enabled on your instance.
3.  A valid n8n API key with the necessary permissions to manage workflows.
4.  Jeedom version 4.2.0 or higher.
5.  The PHP `cURL` extension must be installed and enabled on your Jeedom system.

## Installation

1.  Install the "n8n Connect" plugin directly from the Jeedom Market.
2.  After installation, activate the plugin in **Plugins > Plugin Management**.

## Configuration

### 1. Global Plugin Configuration

Access the global plugin configuration via **Plugins > Plugin Management > n8n Connect > Configuration**.

*   **n8n instance URL:** Enter the full address of your n8n instance (e.g., `https://my.n8n.local` or `http://192.168.1.100:5678`).
*   **API Key:** Enter your n8n API key, generated in n8n (**Settings > API**).
*   Click the **"Test"** button to verify the connection to your n8n instance.

### 2. Equipment (Workflows) Configuration

For each n8n workflow you want to control:

1.  Go to **Plugins > n8n Connect**.
2.  Click **"Add"** to create new equipment.
3.  **Equipment name:** Give a meaningful name to your Jeedom equipment (e.g., "Living Room Lights Workflow").
4.  **Workflow:**
    *   Click the refresh button (<i class="fas fa-sync"></i>) to load the list of your available n8n workflows.
    *   Select the desired workflow from the dropdown list.
    *   If the list does not load (e.g., due to an API connection problem), a manual workflow ID entry field will appear. You can find your workflow ID in the n8n interface.
5.  **Webhook URL (Optional):** If you want to trigger this workflow via a "Launch" command, paste the webhook URL of your n8n workflow here. This URL is provided by the "Webhook" node of your n8n workflow.
6.  Configure the **General parameters** (Parent object, Category, Activate/Visible) as needed.
7.  Click **"Save"**. The "Activate", "Deactivate", and "Status" commands will be created automatically. The "Launch" command will be added if a webhook URL has been provided.

## Available Commands

Once the equipment is configured, the following commands will be available:

*   **Activate:** Activates the corresponding workflow in n8n.
*   **Deactivate:** Deactivates the corresponding workflow in n8n.
*   **Launch:** Sends an HTTP POST request to the webhook URL configured for the workflow. This command is only visible if a "Webhook URL" is provided in the equipment configuration.
*   **Status:** A binary information command indicating whether the workflow is active (1) or inactive (0) in n8n.

## Troubleshooting

### HTTP 401 "unauthorized" error

This error indicates an authentication problem when trying to connect to the n8n API.

*   **Check your configuration:** Make sure the **n8n instance URL** and **API Key** are correctly entered in the global plugin configuration.
*   **Test the connection:** Use the **"Test"** button in the global configuration to validate your credentials.
*   **Check n8n:**
    *   Make sure the REST API is enabled in n8n (**Settings > API**).
    *   Verify that your n8n API key is valid and not expired, and that it has the necessary permissions.
    *   Make sure your n8n instance is started and accessible from Jeedom.
*   **Network connectivity:** Check for firewalls or network issues that might prevent Jeedom from communicating with n8n.

### Common error messages

*   **"Missing webhook URL":** The "Launch" command was executed, but no webhook URL is configured for this equipment.
*   **"Webhook error: The requested webhook ... is not registered":** The workflow is not active in n8n, or the webhook URL is incorrect. Make sure the workflow is activated in n8n and the URL is exact.
*   **"Timeout":** Jeedom could not reach your n8n instance within the allotted time. Make sure n8n is online and accessible.
*   **"Invalid n8n API response":** The n8n API returned an unexpected response.

### Diagnostic logs

For more detailed information, consult the plugin logs:
1.  Go to **Tools > Logs**.
2.  Select the **n8nconnect** plugin.
3.  Look for recent error messages to identify the cause of the problem.

## n8n error notifications to Jeedom

To receive n8n workflow error notifications directly in Jeedom, you can configure a global "Error Workflow" in n8n that will send an HTTP request to Jeedom.

### Configuration in n8n

1.  **Create a new workflow** in n8n (or use an existing workflow dedicated to errors).
2.  Add a **"Webhook"** node as a trigger. Configure it to listen for `POST` requests.
3.  Add an **"HTTP Request"** node after the "Webhook" node.
    *   **Method:** `POST`
    *   **URL:** `http://YOUR_JEEDOM_IP/plugins/n8nconnect/core/ajax/n8nconnect.ajax.php?action=receiveErrorNotification`
        *   Replace `YOUR_JEEDOM_IP` with the IP address or domain name of your Jeedom installation.
    *   **Body Content Type:** `JSON`
    *   **JSON Body:** You can send any relevant JSON data. For example, to send error information from the failed workflow, you can use an expression like:
        ```json
        {
          "workflowName": "{{ $json.workflow.name }}",
          "workflowId": "{{ $json.workflow.id }}",
          "executionId": "{{ $json.id }}",
          "error": "{{ $json.error.message }}",
          "stackTrace": "{{ $json.error.stack }}"
        }
        ```
        These variables (`$json.workflow.name`, etc.) are available in the context of an n8n error workflow.
4.  **Activate this workflow** in n8n.
5.  **Configure this workflow as a global "Error Workflow":**
    *   In n8n, go to **Settings > Workflow Error Handling**.
    *   Select the workflow you just created from the "Error Workflow" dropdown list.

### Processing in Jeedom

The n8n Connect plugin will receive these notifications and record them in the plugin logs (`Tools > Logs > n8nconnect`). You can then use Jeedom scenarios to analyze these logs and trigger actions (notifications, alerts, etc.) based on the content of the error messages.