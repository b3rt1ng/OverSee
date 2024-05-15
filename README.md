<h1 align="center">
  Oversee
</h1>

<h4 align="center">A minimal php honeypot

<p align="center">
  <a href="#context">Context</a> •
  <a href="#features">Key Features</a> •
  <a href="#how-to-use">How To Use</a> •
  <a href="#malicious-usage">Download</a>
</p>

![screenshot](https://i.imgur.com/jtGZPCo.png)

# Context

OverSee is a fictional small to medium-sized enterprise that I conceived to illustrate the practical application and benefits of deploying a honeypot within a small company's IT infrastructure. This project is part of a student initiative at Paris Cité University, aimed at demonstrating how even smaller organizations can leverage cybersecurity tools typically associated with larger enterprises. Through this simulation, we seek to provide a comprehensive understanding of how honeypots can serve as both a defensive mechanism and a learning tool.

## User Activity Logger

This PHP project logs user activity and serves content based on the requested page. It records details such as the user's IP address, user agent, request URL, and the response served. The logs are saved in JSON format and you can see <a href="https://github.com/b3rt1ng/OverSee/blob/main/logs/log_2024-05-15.json">here</a> an example of a hacker accessing the /etc/passwd file.

## Features

- Logs user activity including IP address, user agent, request URL, and timestamp.
- Fetches and serves content from a specified directory.
- Generates unique user IDs by hashing the user's IP address and user agent.
- Handles requests and displays content dynamically.
- Maintains logs in JSON format for easy readability and processing.
## How To Use

```bash
# Clone this repository
$ git clone https://github.com/b3rt1ng/OverSee

# Go into the repository
$ cd OverSee

# Run the app on port 80000
$ php -S localhost:8000
```

## Malicious usage

To use the project, simply navigate to `http://127.0.0.1:8000` in your web browser with a query parameter `page` specifying the file you want to display. For example: `http://127.0.0.1:8000?page=../../../../../etc/passwd` would display the passwd file of the hosting device.
