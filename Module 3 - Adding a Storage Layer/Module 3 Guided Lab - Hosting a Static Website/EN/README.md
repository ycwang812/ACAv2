# Module 3 – Guided Lab: Hosting a Static Website
[//]: # "SKU: ILT-TF-200-ACACAD-2    Source Course: ILT-TF-100-ARCHIT-6 branch dev_65"

## Lab overview and objectives
Static websites have fixed content with no backend processing. They can contain HTML pages, images, style sheets, and all files that are needed to render a website. However, static websites do not use server-side scripting or a database. If you want your static webpages to provide interactivity and run programming logic, you can use JavaScript that runs in the user's web browser.

You can easily host a static website on Amazon Simple Storage Service (Amazon S3) by uploading the content and making it publicly accessible. No servers are needed, and you can use Amazon S3 to store and retrieve any amount of data at any time, from anywhere on the web.

After completing this lab, you should be able to:

- Create a bucket in Amazon S3
- Upload content to your bucket
- Enable access to the bucket objects
- Update the website

## Duration

This lab will require approximately **20 minutes** to complete.

## AWS service restrictions
In this lab environment, access to AWS services and service actions might be restricted to the ones that are needed to complete the lab instructions. You might encounter errors if you attempt to access other services or perform actions beyond the ones that are described in this lab.

## Accessing the AWS Management Console

1. At the top of these instructions, choose <span id="ssb_voc_grey">Start Lab</span> to launch your lab.

   A **Start Lab** panel opens, and it displays the lab status.

   <i class="fas fa-info-circle"></i> **Tip**: If you need more time to complete the lab, restart the timer for the environment by choosing the <span id="ssb_voc_grey">Start Lab</span> button again.

2. Wait until the **Start Lab** panel displays the message *Lab status: ready*, then close the panel by choosing the **X**.

3. At the top of these instructions, choose <span id="ssb_voc_grey">AWS</span>.

   This action opens the AWS Management Console in a new browser tab. The system automatically logs you in.

   <i class="fas fa-exclamation-triangle"></i> **Tip**: If a new browser tab does not open, a banner or icon is usually at the top of your browser with the message that your browser is preventing the site from opening pop-up windows. Choose the banner or icon, and then choose **Allow pop-ups**.

4. Arrange the **AWS Management Console** tab so that it displays alongside these instructions. Ideally, you will have both browser tabs open at the same time so that you can follow the lab steps more easily.

   <i class="fas fa-exclamation-triangle"></i> **Do not change the Region unless specifically instructed to do so**.

## Task 1: Creating a bucket in Amazon S3

In this task, you will create an S3 bucket and configure it for static website hosting.

5. In the **AWS Management Console**, on the <span id="ssb_services">Services<i class="fas fa-angle-down"></i></span> menu, choose **S3**.

6. Choose <span id="ssb_orange">Create bucket</span>

   An S3 bucket name is globally unique, and the namespace is shared by all AWS accounts. After you create a bucket, the name of that bucket cannot be used by another AWS account in any AWS Region unless you delete the bucket.

   Thus, for this lab, you will use a bucket name that includes a random number, such as: _website-123_

7. For **Bucket name**, enter: `website-<123>` (replace <_123_> with a random number)

   Public access to buckets is blocked by default. Because the files in your static website will need to be accessible through the internet, you must permit public access.

   - Verify the **AWS Region** is set to **us-east-1** (if it is not, choose the us-east-1 Region)

8. In the **Object Ownership** section, select **ACLs enabled**, then verify **Bucket owner preferred** is selected.

9. Clear **Block all public access**, then select the box that states **I acknowledge that the current settings may result in this bucket and the objects within becoming public**.

10. Choose <span id="ssb_orange">Create bucket</span>.

   You can use tags to add additional information to a bucket, such as a project code, cost center, or owner.

11. Choose the name of your new bucket.

12. Choose the  **Properties** tab.

13. Scroll to the **Tags** panel.

14. Choose <span id="ssb_white">Edit</span> then <span id="ssb_white">Add tag</span> and enter:

    - **Key:** `Department`
    - **Value:** `Marketing`

15. Choose <span id="ssb_orange">Save changes</span> to save the tag.

    Next, you will configure the bucket for static website hosting.

16. Stay in the **Properties** console.

17. Scroll to the **Static website hosting** panel.

18. Choose <span id="ssb_white">Edit</span>

19. Configure the following settings:

    - **Static web hosting:** Enable
    - **Hosting type:** Host a static website
    - **Index document:** `index.html`
      - **Note**: You must enter this value, even though it is already displayed.
    - **Error document:** `error.html`

20. Choose <span id="ssb_orange">Save changes</span>

21. In the **Static website hosting** panel, choose the link under **Bucket website endpoint**.

    You will receive a *403 Forbidden* message because the bucket permissions have not been configured yet. Keep this tab open in your web browser so that you can return to it later.

    Your bucket has now been configured to host a static website.

## Task 2: Uploading content to your bucket

In this task, you will upload the files that will serve as your static website to the bucket.

22. Right-click each of these links and download the files to your computer:

    <i class="fas fa-exclamation-triangle"></i> Ensure that each file keeps the same file name, including the extension.

    - [index.html](../website/index.html)
    - [script.js](../website/script.js)
    - [style.css](../website/style.css)

23. Return to the Amazon S3 console and in the `website-<123>` bucket you created earlier, choose the **Objects** tab.

24. Choose <span id="ssb_orange">Upload</span>

25. Choose <span id="ssb_white">Add files</span>

26. Locate and select the three files that you downloaded.

27. If prompted, choose <i class="far fa-check-square"></i>I acknowledge that existing objects with the same name will be overwritten.

28. Choose <span id="ssb_orange">Upload</span>

    Your files are uploaded to the bucket.

    - Choose <span id="ssb_orange">Close</span>

## Task 3: Enabling access to the objects

Objects that are stored in Amazon S3 are private by default. This ensures that your organization's data remains secure.

In this task, you will make the uploaded objects publicly accessible.

First, confirm that the objects are currently private.

29. Return to the browser tab that showed the *403 Forbidden* message.

30. Refresh <i class="fas fa-sync"></i>the webpage.

    <i class="fas fa-comment"></i> If you accidentally closed this tab, go to the **Properties** tab, and in the **Static website hosting** panel choose the **Endpoint** link again.

    You should still see a *403 Forbidden* message.

    *Analysis*: This response is expected! This message indicates that your static website is being hosted by Amazon S3, but that the content is private.

    You can make Amazon S3 objects public through two different ways:

    - To make either a whole bucket public, or a specific directory in a bucket public, use a *bucket policy*.
    - To make individual objects in a bucket public, use an *access control list (ACL)*.

    It is normally safer to make _individual objects_ public because this avoids accidentally making other objects public. However, if you know that the entire bucket contains no sensitive information, you can use a _bucket policy_.

    You will now configure the individual objects to be publicly accessible.

31. Return to the web browser tab with the Amazon S3 console (but do not close the website tab).

32. Select <i class="far fa-check-square"></i>all three objects.

33. In the <span id="ssb_white">Actions<i class="fas fa-angle-down"></i></span> menu, choose **Make public via ACL**.

    A list of the three objects is displayed.

34. Choose <span id="ssb_white">Make public</span>

    Your static website is now publicly accessible.

35. Return to the web browser tab that has the *403 Forbidden* message.

36. Refresh <i class="fas fa-sync"></i>the webpage.

   You should now see the static website that is being hosted by Amazon S3.

## Task 4: Updating the website

You can change the website by editing the HTML file and uploading it again to the S3 bucket.

Amazon S3 is an _object storage service_, so you must upload the whole file. This action replaces the existing object in your bucket. You cannot edit the contents of an object—instead, the whole object must be replaced.

37. On your computer, load the **index.html** file into a text editor (for example, Notepad or TextEdit).

38. Find the text **Served from Amazon S3** and replace it with `Created by <YOUR-NAME>`, substituting your name for <*YOUR-NAME*> (for example, _Created by Jane_).

39. Save the file.

40. Return to the Amazon S3 console and upload the **index.html** file that you just edited.

41. Select <i class="far fa-check-square"></i>**index.html** and use the **Actions** menu to choose the **Make public via ACL** option again.

42. Return to the web browser tab with the static website and refresh <i class="fas fa-sync"></i>the page.

    Your name should now be on the page.



Your static website is now accessible on the internet. Because it is hosted on Amazon S3, the website has high availability and can serve high volumes of traffic without using any servers.

You can also use your own domain name to direct users to a static website that is hosted on Amazon S3. To accomplish this, you could use the Amazon Route 53 Domain Name System (DNS) service in combination with Amazon S3.

## Submitting your work

**Tip**: the grading script for this lab assumes that you only have one bucket with the word `website` in the name. Verify that this is the case, and if necessary delete any older buckets you may have created so that the grading script will grade the contents and settings of the correct bucket.

43. At the top of these instructions, choose <span id="ssb_blue">Submit</span> to record your progress and when prompted, choose **Yes**.

44. If the results don't display after a couple of minutes, return to the top of these instructions and choose <span id="ssb_voc_grey">Grades</span>

   **Tip**: You can submit your work multiple times. After you change your work, choose **Submit** again. Your last submission is what will be recorded for this lab.

45. To find detailed feedback on your work, choose <span id="ssb_voc_grey">Details</span> followed by <i class="fas fa-caret-right"></i> **View Submission Report**.


## Lab complete <i class="fas fa-graduation-cap"></i>

<i class="fas fa-flag-checkered"></i> Congratulations! You have completed the lab.

46. Choose <span id="ssb_voc_grey">End Lab</span> at the top of this page, and then select <span id="ssb_blue">Yes</span> to confirm that you want to end the lab.

    A panel indicates that *DELETE has been initiated... You may close this message box now.*

47. Select the **X** in the top right corner to close the panel.



*©2021 Amazon Web Services, Inc. and its affiliates. All rights reserved. This work may not be reproduced or redistributed, in whole or in part, without prior written permission from Amazon Web Services, Inc. Commercial copying, lending, or selling is prohibited.*
