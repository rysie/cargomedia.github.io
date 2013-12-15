---
layout: post
title: "Server Monitoring with bipbip and CopperEgg"
date: 2013-12-15
owner: Reto
tags: [monitoring,bipbip,saas,devops]
---

### Choosing a server monitoring SaaS provider
Having graphs available for historical and current trends for critical service metrics is quite important in my experience.
They *help understand performance bottlenecks* as well as interconnections between different parts of a multi-server infrastructure.
We've decided to replace our [Cacti](http://www.cacti.net/) installation with a "cloud" solution mainly due to the complexity of configuring cacti in an automated manner.
![Cacti](/img/posts/2013/cacti.png)
While [Percona's cacti templates](http://www.percona.com/doc/percona-monitoring-plugins/) are great, setting them up requires manual intervention.
Let's not talk about adding custom metrics to cacti.

We've evaluated
 [New Relic](http://newrelic.com/server-monitoring),
 [Server Density](http://www.serverdensity.com/),
 [Scout](https://scoutapp.com/) and
 [CopperEgg](http://copperegg.com/).
All of these tools let you install an agent on your servers which collects data and sends it back to the provider where you can view graphs, configure alerts etc.
They mainly differ in the ways they allow you to plot metrics and create dashboard as well as how you can collect your own data.

<!--more-->

![CopperEgg](/img/posts/2013/copperegg.png)
We've decided to give *CopperEgg* a try because of the 5 seconds time resolution and the nice HTTP API with which one can add metrics and dashboards in a fully automated fashion.

### Collecting data from mysql, gearman, memcached etc.
