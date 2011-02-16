<div style="font-family: Verdana, Arial;">
  <h1 style="background: #0055A4; color:#ffffff;padding:5px;">
    Summary
  </h1>
  <table cellspacing="1" width="100%">
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Message:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['message'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Environment:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
        	<?php echo $lx_error['environment'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Type:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['type'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Generated At:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['created_at'] ?>
        </pre>
      </td>
    </tr>
  </table>

  <h1 style="background: #0055A4; color:#ffffff;padding:5px;">
    Exception/Error details
  </h1>
  <table cellspacing="1" width="100%">
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="180px">
        Class/Type:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['class'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Code:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['code'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Message:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['message'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        File:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['file'] ?>, Line: <?php echo $lx_error['line'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Trace:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['trace'] ?>
        </pre>
      </td>
    </tr>
  </table>

  <h1 style="background: #0055A4; color:#ffffff;padding:5px;">
    Server
  </h1>
  <table cellspacing="1" width="100%">
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Module:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['module'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Action:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['action'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        URL:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['url'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        User-agent:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['user_agent'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Server:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['server'] ?>
        </pre>
      </td>
    </tr>
    <tr style="padding: 4px;spacing: 0;text-align: left;">
      <th style="background:#cccccc" width="140px">
        Session:
      </th>
      <td style="padding: 4px;spacing: 0;text-align: left;background:#eeeeee">
        <pre style='margin: 0px 0px 10px 0px; display: block; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 15px; line-height: 13px;'>
          <?php echo $lx_error['session'] ?>
        </pre>
      </td>
    </tr>
  </table>
</div>
