# 模块 4 – 引导式实验：Amazon Elastic File System (Amazon EFS) 简介
[//]:# "SKU：ILT-TF-200-ACACAD-2    源课程：SPL-151"

## 实验概览与目标
本实验旨在介绍如何通过 AWS 管理控制台使用 Amazon Elastic File System (Amazon EFS)。

完成本实验后，您应当能够：

- 登录 AWS 管理控制台

- 创建 Amazon EFS 文件系统

- 登录到运行 Amazon Linux 的 Amazon Elastic Compute Cloud (Amazon EC2) 实例

- 将文件系统挂载到 EC2 实例

- 检查和监控文件系统的性能

<br/>
## 时长

完成本实验大约需要 **20** 分钟。

<br/>
## AWS 服务限制

本实验环境中对 AWS 服务和服务操作的访问可能仅以完成实验说明为限。如果您尝试访问其他服务或者执行本实验所述之外的操作，可能会出错。

<br/>

## 访问 AWS 管理控制台

1. 在本说明上方，选择 <span id="ssb_voc_grey">Start Lab</span>（开始实验）以启动实验。

   此时将打开 **Start Lab**（开始实验）面板，其中显示实验状态。

   <i class="fas fa-info-circle"></i> **提示**：如果您需要更多时间来完成实验，请重新选择 <span id="ssb_voc_grey">Start Lab</span>（开始实验）按钮来重新启动环境的计时器。

2. 请等待 **Start Lab**（开始实验）面板显示消息 *Lab status: ready*（实验状态：就绪）后，再选择 **X** 关闭该面板。

3. 在本说明上方，选择 <span id="ssb_voc_grey">AWS</span>。

   此操作将会在新的浏览器标签页中打开 AWS 管理控制台。您将自动登录系统。

   <i class="fas fa-exclamation-triangle"></i> **提示**：如果未打开新的浏览器标签页，那么您的浏览器顶部通常会有一个横幅或图标，同时显示一条消息，指明您的浏览器阻止该网站打开弹出窗口。选择该横幅或图标，然后选择 **Allow pop ups**（允许弹出窗口）。

4. 排列 **AWS 管理控制台**标签页，使其与本说明并排显示。理想情况下，您将同时打开这两个浏览器标签页，从而更轻松地执行实验步骤。

   <i class="fas fa-exclamation-triangle"></i> **除非特别要求，否则请勿更改区域**。

<br/>

## 任务 1：创建安全组以访问 EFS 文件系统

与挂载目标关联的安全组*必须允许网络文件系统 (NFS, Network File System) 的端口 2049 的 TCP 入站访问*。这是您即将创建、配置并附加到 EFS 挂载目标的安全组。


5. 在 **AWS 管理控制台**的 <span id="ssb_services">Services</span>（服务）菜单上，选择 **EC2**。

6. 在左侧导航窗格中，选择 **Security Groups**（安全组）。

7. 将 *EFSClient* 安全组的 **Security group ID**（安全组 ID）复制到文本编辑器中。

   组 ID 应类似于 *sg-03727965651b6659b*。

8. 选择 <span id="ssb_orange">Create security group</span>（创建安全组），然后进行以下配置：

   <a id='securitygroup'></a>

    * **Security group name**（安全组名称）：`EFS Mount Target`
    * **Description**（描述）：`Inbound NFS access from EFS clients`
    * **VPC：** *Lab VPC*

9. 在 **Inbound rules**（入站规则）部分中，选择 <span id="ssb_white">Add rule</span>（添加规则），然后进行以下配置：

    * **Type**（类型）：*NFS*
    * **Source**（源）：
      * *Custom*（自定义）
      * 在 *Custom*（自定义）框中，粘贴复制到文本编辑器中安全组的 **Security group ID**（安全组 ID）
    * 选择 <span id="ssb_orange">Create security group</span>（创建安全组）。

<br/>

## 任务 2：创建 EFS 文件系统

EFS 文件系统可以挂载到在同一区域内的不同可用区中运行的多个 EC2 实例。这些实例通过标准 NFSv4.1 语义，使用在每个*可用区*中创建的*挂载目标*来挂载文件系统。您一次只能在一个 virtual private cloud (VPC) 中的实例上挂载文件系统。文件系统和 VPC 必须位于同一区域中。


10. 在 <span id="ssb_services">Services</span>（服务）菜单上，选择 **EFS**。

11. 选择 <span id="ssb_orange">Create file system</span>（创建文件系统）

12. 在 **Create file system**（创建文件系统）窗口中，选择 <span id="ssb_white">Customize</span>（自定义）

13. 在**步骤 1** 中：

    - 取消选中 <i class="far fa-square"></i> **Enable automatic backups**（启用自动备份）。
    - **Lifecycle management**（生命周期管理）：选择 *None*（无）
    - 在 **Tags**（标签）部分中，进行以下配置：
      - **Key**（键）：`Name`
      - **Value**（值）：`My First EFS File System`

14. 选择 <span id="ssb_orange">Next</span>（下一步）

15. 对于 **VPC**，选择 *Lab VPC*。

16. 通过选中每个默认安全组上的 <i class="fas fa-times"></i> 复选框，从每个*可用区*挂载目标分离默认安全组。

17. 通过以下方式将 **EFS 挂载目标**安全组附加到每个*可用区*挂载目标：

   * 选中每个 **Security groups**（安全组）复选框。
   * 选择 **EFS Mount Target**（EFS 挂载目标）。

     此时将为每个子网创建一个挂载目标。

     您的挂载目标应如以下示例所示。该图显示了 **Lab VPC** 中使用 **EFS Mount Target**（EFS 挂载目标）安全组的两个挂载目标。在本实验中，您应使用 **Lab VPC**。

     <img src="images/mount-targets-security-groups.png" alt="目标安全组" width="600" >

18. 选择 <span id="ssb_orange">Next</span>（下一步）

19. 在**步骤 3** 中，选择 <span id="ssb_orange">Next</span>（下一步）

20. 在**步骤 4** 中：

  * 查看配置。
  * 选择 <span id="ssb_orange">Create</span>（创建）

<i class="far fa-thumbs-up"></i> 恭喜！您已在 Lab VPC 中创建新的 EFS 文件系统，并在每个 Lab VPC 子网中创建了多个挂载目标。几秒钟后，文件系统的 **File system state**（文件系统状态）将变成 *Available*（可用），在 2 到 3 分钟后，挂载目标的状态将发生更改。

在每个挂载目标的 **Mount target state**（挂载目标状态）变成 *Available*（可用）之后，继续下一步。2 到 3 分钟后，选择屏幕刷新按钮以查看其进度。

**注意**：您可能需要在 **File systems**（文件系统）窗格中滚动至右侧，来查找 **File system state**（文件系统状态）。

<br/>

## 任务 3：通过 SSH 连接到 EC2 实例

在此任务中，您将使用 Secure Shell (SSH) 连接到 EC2 实例。

### <i class="fab fa-windows"></i> Microsoft Windows 用户

<i class="fas fa-comment"></i> 本说明专供 Microsoft Windows 用户使用。如果您使用的是 macOS 或 Linux，请<a href="#ssh-MACLinux">跳至下一部分</a>。
​

21. 在当前说明的上方，选择 <span id="ssb_voc_grey">Details</span>（详细信息）下拉菜单，然后选择 <span id="ssb_voc_grey">Show</span>（显示）

   此时将打开 **Credentials**（凭证）窗口。

22. 选择 **Download PPK**（下载 PPK）按钮并保存 **labsuser.ppk** 文件。

   **注意**：通常，浏览器会将该文件保存到 **Downloads** 目录中。

23. 记下 **EC2PublicIP** 地址（如果已显示）。

24. 选择 **X** 以退出 **Details**（详细信息）面板。

25. 要使用 SSH 访问 EC2 实例，您必须使用 ***\*PuTTY\****。如果您的电脑上没有安装 PuTTY，请<a href="https://the.earth.li/~sgtatham/putty/latest/w64/putty.exe">下载 PuTTY</a>。

26. 打开 **putty.exe**。

27. 要使 PuTTY 会话在更长时间内保持打开状态，请配置 PuTTY 超时：

   * 选择 **Connection**（连接）
   * **Seconds between keepalives**（两次 keepalive 之间的秒数）：`30`

28. 使用以下设置配置 PuTTY 会话。

   * 选择 **Session**（会话）
   * **Host Name (or IP address)**（主机名（或 IP 地址））：粘贴您之前记下的实例的 **EC2PublicIP**
     * 或者，返回 Amazon EC2 控制台，然后选择 **Instances**（实例）
     * 选择要连接到的实例
     * 在 *Description*（描述）标签页中，复制 **IPv4 Public IP**（IPv4 公有 IP）值
   * 回到 PuTTY，在 **Connection**（连接）列表中，展开 <i class="far fa-plus-square"></i> **SSH**
   * 选择 **Auth**（身份验证），然后展开 <i class="far fa-plus-square"></i> **Credentials**（凭证）
   * 在* *Private key file for authentication**（用于身份验证的私有密钥文件）：下方，选择 **Browse**（浏览）
   * 浏览找到已下载的 *labsuser.ppk* 文件，选择该文件，然后选择 **Open**（打开）
   * 再次选择 **Open**（打开）


29. 要信任主机并连接到该主机，请选择 **Accept**（接受）。

30. 在系统提示您输入 **login as**（登录身份）时，请输入：`ec2-user`

    此操作会将您连接到 EC2 实例。

31. Microsoft Windows 用户：<a href="#ssh-after">选择此链接跳至下一个任务。</a>



<a id='ssh-MACLinux'></a>

### macOS <span style="font-size: 30px; color: #808080;"><i class="fab fa-apple"></i></span> 和 Linux <span style="font-size: 30px; "><i class="fab fa-linux"></i></span> 用户

本说明专供 macOS 或 Linux 用户使用。如果您是 Windows 用户，<a href="#ssh-after">请跳至下一任务。</a>

32. 在当前说明的上方，选择 <span id="ssb_voc_grey">Details</span>（详细信息）下拉菜单，然后选择 <span id="ssb_voc_grey">Show</span>（显示）

    此时将打开 **Credentials**（凭证）窗口。

33. 选择 **Download PEM**（下载 PEM）按钮并保存 **labsuser.pem** 文件。

34. 记下 **EC2PublicIP** 地址（如果已显示）。

35. 选择 **X** 以退出 **Details**（详细信息）面板。

36. 打开一个终端窗口，并使用 `cd` 命令将目录更改为下载的 *labsuser.pem* 文件所在的目录。

    例如，如果 *labsuser.pem* 文件已保存到 **Downloads** 目录，请运行此命令：

    ```bash
    cd ~/Downloads
    ```

37. 运行此命令，将密钥的权限更改为只读：

    ```bash
    chmod 400 labsuser.pem
    ```

38. 运行以下命令（将 **<public-ip\>** 替换为之前复制的 **EC2PublicIP** 地址）。

    * 或者，要查找本地部署实例的 IP 地址，请返回 Amazon EC2 控制台并选择 **Instances**（实例）
    * 选择要连接到的实例
    * 在 **Description**（描述）标签页中，复制 **IPv4 Public IP**（IPv4 公有 IP）值

     ```bash
     ssh -i labsuser.pem ec2-user@<public-ip>
     ```

39. 当系统提示允许首次连接此远程 SSH 服务器时，输入 `yes`。

    由于您使用密钥对进行身份验证，系统不会提示您输入密码。

<a id='ssh-after'></a>

<br/>
## 任务 4：创建新目录并挂载 EFS 文件系统

<i class="fas fa-info-circle" aria-hidden="true"></i> Amazon EFS 在 EC2 实例上挂载文件系统时支持 NFSv4.1 和 NFSv4.0 协议。尽管也支持 NFSv4.0，但我们仍建议您使用 NFSv4.1。在 EC2 实例上挂载 EFS 文件系统时，也必须使用支持所选 NFSv4 协议的 NFS 客户端。本实验中启动的 EC2 实例包括已安装在该实例上的 NFSv4.1 客户端。


40. 在 SSH 会话中，输入 `sudo mkdir efs` 来创建新目录

41. 返回 **AWS 管理控制台**，在 <span id="ssb_services">Services</span>（服务）菜单上，选择 **EFS**。

42. 选择 **My First EFS File System**（我的第一个 EFS 文件系统）。

43. 在 **Amazon EFS Console**（Amazon EFS 控制台）的页面右上角，选择 <span id="ssb_orange">Attach</span>（附加）以打开 Amazon EC2 挂载说明。

44. 复制 **Using the NFS client**（使用 NFS 客户端）部分中的完整命令。

    挂载命令应类似于以下示例：

    `sudo mount -t nfs4 -o nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport fs-bce57914.efs.us-west-2.amazonaws.com:/ efs`

    <i class="fas fa-comment" aria-hidden="true"></i> 提供的 `sudo mount...` 命令使用默认 Linux 挂载选项。

45. 在 Linux SSH 会话中，通过以下方式挂载 Amazon EFS 文件系统：

    * 粘贴该命令
    * 按 Enter 键


46. 输入以下内容，获取可用和已用磁盘空间使用情况的完整摘要：

    `sudo df -hT`

    以下屏幕截图是 *disk filesystem* 命令的输出示例：

    `df -hT`
    
    请注意已挂载的 EFS 文件系统的*类型*和*大小*。

<img src="images/disk-space.png" alt="磁盘空间" width="600"></img>


<br/>
## 任务 5：检查新 EFS 文件系统的性能表现


### 使用 Flexible IO 检查性能

<i class="fas fa-info-circle"></i> Flexible IO (fio) 是用于 Linux 的合成输入/输出基准测试实用工具。该工具用于对 Linux 输入/输出子系统进行基准测试和测试。在启动过程中，*fio* 会自动安装在 EC2 实例上。

47. 通过输入以下内容来检查文件系统的写入性能特征：

    ```
    sudo fio --name=fio-efs --filesize=10G --filename=./efs/fio-efs-test.img --bs=1M --nrfiles=1 --direct=1 --sync=0 --rw=write --iodepth=200 --ioengine=libaio
    ```

    <i class="fas fa-comment"></i> `fio` 命令将需要 5 到 10 分钟才能完成。输出应类似于以下屏幕截图中的示例。请务必检查 `fio` 命令的输出，特别是此写入测试的摘要状态信息。

    <img src="images/fio.png" alt="fio" width="600" >

<br/>
### 使用 Amazon CloudWatch 监控性能

48. 在 **AWS 管理控制台**的 <span id="ssb_services">Services</span>（服务）菜单上，选择 **CloudWatch**。

49. 在左侧导航窗格中，选择 **Metrics**（指标）。

50. 在 **All metrics**（所有指标）标签页中，选择 **EFS**。

51. 选择 **File System Metrics**（文件系统指标）。

52. 选择包含 **PermittedThroughput** 指标名称的行。

    <i class="fas fa-comment"></i> 您可能需要等待 2 到 3 分钟并多次刷新屏幕，以便系统计算和填充所有可用指标（包括 **PermittedThroughput**）。

53. 在图表中，选择并拖动数据行。如果看不到折线图，请调整折线图的时间范围，以显示运行 `fio` 命令的时段。

    <img src="images/graph.png" alt="选择拖动" width="600" >


54. 将指针停在图表中的数据行上。该值应为 *105M*。

    <img src="images/throughput.png" alt="吞吐量" width="600" >
    
    
    Amazon EFS 的吞吐量会随着文件系统的增大而扩展。基于文件的工作负载通常是高峰工作负载。它们会在短时间内产生高水平的吞吐量，而在其余时间产生低水平的吞吐量。鉴于此行为，Amazon EFS 设计为在一段时间内可突增到高吞吐量水平。不管大小如何，所有文件系统都能突增到 100MiB/s 的吞吐量。有关 EFS 文件系统性能特征的更多信息，请参阅官方 <a href="http://docs.aws.amazon.com/efs/latest/ug/performance.html" target="_blank">Amazon Elastic File System 文档</a>。
    
55. 在 **All metrics**（所有指标）标签页中，*取消选中* **PermittedThroughput** 对应的框。

56. 选中 **DataWriteIOBytes** 对应的复选框。

    <i class="fas fa-comment"></i> 如果指标列表中未显示 *DataWriteIOBytes*，请使用 **File System Metrics**（文件系统指标）搜索来查找该指标。

57. 选择 **Graphed metrics**（图形化指标）标签页。

58. 在 **Statistics**（统计信息）列中，选择 **Sum**（总计）。

59. 在 **Period**（周期）列中，选择 **1 Minute**（1 分钟）。

60. 将指针停在折线图中的峰值上。取该峰值（以字节为单位），然后除以持续时间（60 秒），即可得出测试期间文件系统的写入吞吐量（以 B/s 为单位）。

    <img src="images/Sum-1-minute.png" alt="总计 1 分钟" width="600" >

    文件系统可用的吞吐量会随着文件系统的增大而扩展。所有文件系统都提供一致的基准测试性能，即每 TiB 存储 50 MiB/s。此外，不管大小如何，所有文件系统都能突增到 100MiB/s。大于 1TB 的文件系统可以突增至每 TiB 存储 100MiB/s。当您向文件系统中添加数据时，文件系统可用的最大吞吐量会随着存储量自动线性扩展。

    连接到一个文件系统的所有 EC2 实例可以共享文件系统的吞吐量。有关 EFS 文件系统性能特征的更多信息，请参阅官方 <a href="http://docs.aws.amazon.com/efs/latest/ug/performance.html" target="_blank">Amazon Elastic File System 文档</a>。

    <i class="far fa-thumbs-up" style="color:blue"></i> 恭喜！您创建了 EFS 文件系统并将其挂载到了 EC2 实例，同时运行了输入/输出基准测试来检查其性能特征。
    

<br/>
## 提交作业

61. 在本说明上方，选择 <span id="ssb_blue">Submit</span>（提交）以记录您的进度，并在出现提示时选择 **Yes**（是）。

62. 如果在几分钟后仍未显示结果，请返回到本说明上方，并选择 <span id="ssb_voc_grey">Grades</span>（成绩）

    **提示**：您可以多次提交作业。更改作业后，再次选择 **Submit**（提交）。您最后一次提交的作业将记为本实验内容的作业。

63. 要查找有关作业的详细反馈，请选择 <span id="ssb_voc_grey">Details</span>（详细信息），然后选择 <i class="fas fa-caret-right"></i> **View Submission Report**（查看提交报告）。


<br/>
## 实验完成 <i class="fas fa-graduation-cap"></i>

<i class="fas fa-flag-checkered"></i> 恭喜！您已完成本实验。


64. 选择此页面顶部的 <span id="ssb_voc_grey">End Lab</span>（结束实验），然后选择 <span id="ssb_blue">Yes</span>（是）确认您要结束实验。

    此时应显示一个面板，其中包含这样一条消息：*DELETE has been initiated...You may close this message box now.*（删除操作已启动... 您现在可以关闭此消息框）。

65. 选择右上角的 **X**，关闭面板。





*©2023 Amazon Web Services, Inc. 和其附属公司。保留所有权利。未经 Amazon Web Services, Inc. 事先书面许可，不得复制或转载本文的部分或全部内容。禁止因商业目的复制、出借或出售本文。*