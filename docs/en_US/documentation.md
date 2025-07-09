# n8n Connect Plugin Configuration and Troubleshooting

This document provides a detailed guide for configuring the n8n Connect plugin in Jeedom, as well as solutions to common problems you may encounter.

## Table of Contents

1.  [Prerequisites](#1-prerequisites)
2.  [Plugin Installation](#2-plugin-installation)
3.  [Global Plugin Configuration](#3-global-plugin-configuration)
    *   [Accessing Configuration](#accessing-configuration)
    *   [n8n Connection Parameters](#n8n-connection-parameters)
    *   [Connection Test](#connection-test)
4.  [Equipment (Workflows) Configuration](#4-equipment-workflows-configuration)
    *   [Creating New Equipment](#creating-new-equipment)
    *   [General Equipment Parameters](#general-equipment-parameters)
    *   [Specific Workflow Parameters](#specific-workflow-parameters)
    *   [Saving Equipment](#saving-equipment)
5.  [Available Commands](#5-available-commands)
    *   [Action Commands](#action-commands)
    *   [Information Commands](#information-commands)
6.  [Troubleshooting and Common Errors](#6-troubleshooting-and-common-errors)
    *   [HTTP 401 "unauthorized" Error](#http-401-unauthorized-error)
    *   ["Missing webhook URL"](#missing-webhook-url)
    *   ["Webhook error: The requested webhook ... is not registered"](#webhook-error-the-requested-webhook--is-not-registered)
    *   ["Timeout"](#timeout)
    *   ["Invalid n8n API response"](#invalid-n8n-api-response)
    *   [Diagnostic Logs](#diagnostic-logs)
7.  [Support](#7-support)

---

## 1. Prerequisites

Before starting the configuration, make sure the following elements are in place:

*   **n8n Instance:** A functional n8n instance accessible from your Jeedom installation. This can be a local instance, on a private network, or a cloud instance.
*   **n8n REST API enabled:** The REST API must be enabled in your n8n instance settings. You will usually find it under `Settings > API`.
*   **n8n API Key:** A valid API key generated in n8n. This key must have the necessary permissions to:
    *   List workflows.
    *   Activate/Deactivate workflows.
    *   (Optional) Execute workflows via the API if you use this method (although the plugin prefers webhooks for launching).
*   **Jeedom:** A Jeedom installation version 4.2.0 or higher.
*   **PHP cURL Extension:** The PHP `cURL` extension is essential for the plugin to communicate with the n8n API. Make sure it is installed and enabled on your Jeedom system.

## 2. Plugin Installation

1.  **Via the Jeedom Market:** Access your Jeedom interface, then go to `Plugins > Plugin Management > Market`. Search for "n8n Connect" and install it.
2.  **Activation:** Once the installation is complete, the plugin will appear in your plugin list. Click the `Activate` button (usually a green checkmark icon) to make it operational.

## 3. Global Plugin Configuration

This step establishes the connection between your Jeedom and your n8n instance.

### Accessing Configuration

*   In Jeedom, navigate to `Plugins > Plugin Management`.
*   Locate "n8n Connect" in the list and click its icon (usually a wrench) or the `Configuration` button.

### n8n Connection Parameters

On the configuration page, you will find the following fields:

*   **n8n instance URL:**
    *   Enter the full address of your n8n instance.
    *   **Examples:**
        *   `https://my.n8n.local` (for an instance with SSL/TLS)
        *   `http://192.168.1.100:5678` (for a local instance without SSL/TLS, with the default port)
    *   Make sure the URL is accessible from the Jeedom server.
*   **API Key:**
    *   Copy and paste the API key you generated in your n8n instance (under `Settings > API`).
    *   **Warning:** Never share this key. It grants access to your n8n instance.

### Connection Test

*   After entering the URL and API Key, click the **"Test"** button.
*   Jeedom will attempt to connect to your n8n instance and retrieve a list of workflows to verify the validity of the information provided.
*   A success or error message will be displayed, indicating whether the connection is established correctly.

## 4. Equipment (Workflows) Configuration

Each Jeedom equipment represents a specific n8n workflow you want to control.

### Creating New Equipment

1.  In Jeedom, go to `Plugins > n8n Connect`.
2.  Click the **"Add"** button to create new equipment.

### General Equipment Parameters

*   **Equipment name:** Give a clear and descriptive name to your Jeedom equipment (e.g., "Garden Watering Workflow", "Notifications Workflow").
*   **Parent object:** Associate the equipment with an existing Jeedom object (e.g., "Garden", "Home").
*   **Category:** Assign one or more categories to the equipment (e.g., "Light", "Security").
*   **Options:**
    *   **Activate:** Check this box to activate the equipment in Jeedom.
    *   **Visible:** Check this box to make the equipment visible on the Jeedom Dashboard.

### Specific Workflow Parameters

*   **Workflow:**
    *   Click the refresh button (<i class="fas fa-sync"></i>) next to the field to load the list of all available workflows on your n8n instance.
    *   Select the desired n8n workflow that this equipment should control from the dropdown list.
    *   **Error case:** If the list does not load (e.g., due to an API connection problem or if no workflows are found), a manual workflow ID entry field will appear. You can find your workflow ID in the n8n editor URL (e.g., `https://your.n8n.instance/workflow/YOUR_WORKFLOW_ID`).
*   **Webhook URL (Optional):**
    *   If you want to be able to trigger this n8n workflow via the "Launch" command from Jeedom, you must enter its webhook URL.
    *   This URL is generated by the "Webhook" node of your n8n workflow. Copy the complete URL (e.g., `https://your.n8n.instance/webhook/your-unique-path`).
    *   **Important:** If this field is empty, the "Launch" command will not be available for this equipment.
*   **Auto-refresh:** (If available) Allows you to define the frequency at which Jeedom should refresh the workflow status (active/inactive) from n8n. Use the cron helper to define a schedule.

### Saving Equipment

*   Once all parameters are configured, click the **"Save"** button at the top of the page.
*   Jeedom will save the equipment and automatically create the associated commands (Activate, Deactivate, Status, and Launch if the webhook is configured).

## 5. Available Commands

After saving the equipment, the following commands will be accessible:

### Action Commands

*   **Activate:** Sends a request to n8n to activate the workflow associated with this equipment. The workflow will start executing according to its configuration (e.g., on a trigger).
*   **Deactivate:** Sends a request to n8n to deactivate the workflow. The workflow will stop executing and will no longer respond to its triggers.
*   **Launch:** (Visible only if a "Webhook URL" is configured for the equipment). Sends an HTTP `POST` request to the specified webhook URL. This will trigger the execution of the n8n workflow as if the webhook had been called externally.

### Information Commands

*   **Status:** A binary information command (`0` or `1`) that indicates the current status of the workflow in n8n:
    *   `1` (Active): The workflow is activated and ready to execute.
    *   `0` (Inactive): The workflow is deactivated.
    *   This information is updated during auto-refresh or after an activate/deactivate action.

## 6. Troubleshooting and Common Errors

Here are the most frequently encountered problems and how to solve them.

### HTTP 401 "unauthorized" Error

**Description:** This error indicates an authentication problem when attempting to connect to the n8n API.

**Possible causes:**
*   Missing, incorrect, or expired API key.
*   The REST API is not enabled in n8n.
*   The n8n instance URL is incorrect or inaccessible.
*   API key permissions issue.

**Solutions:**
1.  **Check your global plugin configuration:** Make sure the **n8n instance URL** and **API Key** are correctly entered in `Plugins > Plugin Management > n8n Connect > Configuration`.
2.  **Test the connection:** Use the **"Test"** button on this same page to validate your credentials and instance accessibility.
3.  **Check n8n:**
    *   In your n8n instance, go to `Settings > API` and make sure the REST API is enabled.
    *   Verify that the API key you are using is indeed the one generated here, that it has not expired, and that it has the necessary permissions (at least `workflows.read`, `workflows.write`, `workflows.activate`, `workflows.deactivate`).
    *   Make sure your n8n instance is started and running correctly.
4.  **Network connectivity:** Check for firewalls or network routing issues that might prevent Jeedom from communicating with n8n on the specified port.

### "Missing webhook URL"

**Description:** This message appears when you try to execute the "Launch" command for an equipment, but the "Webhook URL" field is empty in its configuration.

**Solution:**
*   Edit the affected equipment (`Plugins > n8n Connect`, click on the equipment).
*   In the specific parameters, enter the complete webhook URL of your n8n workflow in the **"Webhook URL"** field.
*   Save the equipment. The "Launch" command should now work.

### "Webhook error: The requested webhook ... is not registered"

**Description:** n8n indicates that it cannot find the webhook corresponding to the URL or that the workflow is not active.

**Possible causes:**
*   The workflow is not activated in n8n. Production webhooks only work if the workflow is active.
*   The webhook URL entered in Jeedom is incorrect (typo, wrong webhook ID, etc.).
*   The "Webhook" node in your n8n workflow is not configured to accept `POST` requests (although this is the default behavior).

**Solutions:**
1.  **Activate the workflow in n8n:** Open your workflow in n8n and make sure the `Active` button (top right of the editor) is set to `ON`.
2.  **Check the webhook URL:** Copy the webhook URL directly from the "Webhook" node of your n8n workflow and paste it again into the "Webhook URL" field of the Jeedom equipment to avoid any errors.
3.  **HTTP Method:** The plugin sends a `POST` request. Make sure your "Webhook" node in n8n is configured to accept `POST` requests (this is the default for production webhooks).

### "Timeout"

**Description:** Jeedom did not receive a response from n8n within the allotted time (30 seconds by default).

**Possible causes:**
*   Your n8n instance is stopped or unresponsive.
*   Network connectivity problem between Jeedom and n8n (firewall, router, etc.).
*   The n8n instance is overloaded or very slow to respond.

**Solutions:**
1.  **Check n8n status:** Make sure your n8n instance is running and accessible via a browser or a `ping` from the Jeedom server.
2.  **Check connectivity:** Test the network connection between your Jeedom and n8n. For example, from your Jeedom terminal, try `curl -v YOUR_N8N_URL`.
3.  **n8n performance:** If n8n is overloaded, consider optimizing your workflows or increasing the resources allocated to your n8n instance.

### Diagnostic Logs

For more detailed information on errors, consult the n8n Connect plugin logs:

1.  In Jeedom, go to `Tools > Logs`.
2.  In the dropdown list, select `n8nconnect`.
3.  The logs display communications between Jeedom and n8n, including requests sent and responses received, which is crucial for troubleshooting.

## Workflow error notifications

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

## 7. Support

If you encounter persistent problems after following this guide, please collect the following information before asking for help:

*   Exact Jeedom version (visible in `Settings > System > Configuration > General`).
*   Your n8n instance version.
*   Complete and exact error messages, copied directly from the `n8nconnect` Jeedom logs.
*   Screenshot of the global plugin configuration page (hide your API key).
*   Screenshot of the concerned Jeedom equipment configuration page.
*   Detailed description of the steps to reproduce the problem.