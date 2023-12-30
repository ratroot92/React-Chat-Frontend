/* eslint-disable @typescript-eslint/ban-ts-comment */
import nodemailer from 'nodemailer';
import { IModuleDoc } from '../models/Cohort';
import { IUserDoc } from '../models/User';

type EmailTemplates = 'CLIENT_APPROVED' | 'CLIENT_DISAPPROVED' | 'RESET_CLIENT_PASSWORD' | 'CLIENT_PASSWORD_CHANGED' | 'CLIENT_MODULE_ASSIGNED' | 'CLIENT_MODULE_UNASSIGNED';

enum ENUM_EMAIL_TEMPLATES {
  CLIENT_APPROVED = 'CLIENT_APPROVED',
  CLIENT_DISAPPROVED = 'CLIENT_DISAPPROVED',
  RESET_CLIENT_PASSWORD = 'RESET_CLIENT_PASSWORD',
  CLIENT_PASSWORD_CHANGED = 'CLIENT_PASSWORD_CHANGED',
  CLIENT_MODULE_ASSIGNED = 'CLIENT_MODULE_ASSIGNED',
  CLIENT_MODULE_UNASSIGNED = 'CLIENT_MODULE_UNASSIGNED',
}

export interface IMailOptions {
  from: string;
  to: string;
  subject: string;
  text: string;
}

export interface ICLientModuleAssigned {
  user: IUserDoc;
  module?: IModuleDoc;
}

export interface IEmailTemplateData {
  recipient: string;
  template: EmailTemplates;
  info: ICLientModuleAssigned;
}

class EmailManager {
  smtpPort: number;
  smtpIsSecure: boolean;
  smtpUsername: string;
  smtpPassword: string;
  sendEmail: boolean;

  constructor() {
    this.smtpPort = Number(process.env.GMAIL_SMTP_PORT);
    this.smtpIsSecure = JSON.parse(process.env.GMAIL_SMTP_SECURE || 'false');
    //@ts-ignore
    this.smtpUsername = process.env.GMAIL_SMTP_USERNAME;
    //@ts-ignore
    this.smtpPassword = process.env.GMAIL_SMTP_PASSWORD;
    this.sendEmail = JSON.parse(process.env.SEND_EMAIL || 'false');
  }

  private getGmailTransport() {
    return nodemailer.createTransport({
      service: 'Gmail',
      port: this.smtpPort,
      secure: this.smtpIsSecure,
      auth: {
        user: this.smtpUsername,
        pass: this.smtpPassword,
      },
    });
  }

  public getEmailTemplate(options: IEmailTemplateData): IMailOptions | any {
    const { template, info, recipient } = options;
    switch (template) {
      case ENUM_EMAIL_TEMPLATES.CLIENT_APPROVED: {
        return {
          from: `ISH-Admin ${process.env.GMAIL_SMTP_USERNAME}`,
          to: recipient,
          subject: 'Approval of Your Account Status',
          text: `
          Dear ${info.user.name},

            We are delighted to inform you that your account has been approved by the administrator.
          You can now access the content and features available to you on the LMS. Feel free to log in and enjoy the enriching content we have to offer.
          If you encounter any issues or have any queries, our team will be ready to assist. You can reach out to us at ishfrontdesk@gmail.com.
          Thank you for being part of ISH. We look forward to providing you with a valuable experience.

          Sincerely,
          Admin

          Office of Dr. Omar Minwalla
          The Institute for Sexual Health (ISH)
          www.MinwallaModel.com
          `,
        };
      }

      case ENUM_EMAIL_TEMPLATES.CLIENT_DISAPPROVED: {
        return {
          from: `ISH-Admin ${process.env.GMAIL_SMTP_USERNAME}`,
          to: recipient,
          subject: 'Status Update: Denial of Account Access',
          text: `
            Dear ${info.user.name},

              We regret to inform you that your account was not approved by the administrator. As a result, your access to ISH content has been denied.
            We understand that this may be disappointing news, and we sincerely apologize for any inconvenience this may have caused.
            If you believe this decision is in error or have any questions regarding the status of your account, you can send your queries at ishfrontdesk@gmail.com.
            Thank you for your patience.

           Sincerely,
            Admin

          Office of Dr. Omar Minwalla
          The Institute for Sexual Health (ISH)
          www.MinwallaModel.com
          `,
        };
      }

      case ENUM_EMAIL_TEMPLATES.RESET_CLIENT_PASSWORD: {
        return {
          from: `ISH-Admin ${process.env.GMAIL_SMTP_USERNAME}`,
          to: recipient,
          subject: 'Password Reset OTP Notification',
          text: `
            Dear ${info.user.name},

              We hope this email finds you well.
            As per your request, a One-Time Password (OTP) has been generated for resetting your password. Please use the following OTP to proceed with the password reset process:
            Password Reset OTP: ${info.user.passwordResetOtp}
            Please ensure that you keep this OTP confidential and do not share it with anyone for security purposes.
            If you did not initiate this password reset request, or if you have any concerns about the security of your account, please contact our support team immediately at ishfrontdesk@gmail.com.
            Thank you for your attention to this matter.

            Sincerely,
            Admin

            Office of Dr. Omar Minwalla
            The Institute for Sexual Health (ISH)
            www.MinwallaModel.com
`,
        };
      }

      case ENUM_EMAIL_TEMPLATES.CLIENT_PASSWORD_CHANGED: {
        return {
          from: `ISH-Admin ${process.env.GMAIL_SMTP_USERNAME}`,
          to: recipient,
          subject: 'Password Change Confirmation',
          text: `
            Dear ${info.user.name},

             We are writing to inform you that your password has been successfully changed.
            Your account security is important to us, and we want to ensure that you have full control over your credentials. If you initiated this password change, you can now log in using your updated password.
            If you did not initiate this change or have any concerns about the security of your account, please contact our support team immediately.
            Thank you for using our services, and we look forward to providing you with a secure and valuable experience.

            Sincerely,
            Admin

            Office of Dr. Omar Minwalla
            The Institute for Sexual Health (ISH)
            www.MinwallaModel.com
          `,
        };
      }

      case ENUM_EMAIL_TEMPLATES.CLIENT_MODULE_ASSIGNED: {
        return {
          from: `ISH-Admin ${process.env.GMAIL_SMTP_USERNAME}`,
          to: recipient,
          subject: 'Module Assigned!',
          text: `
            Dear ${info.user.name},

              We are pleased to inform you that you have been granted access to the content for ${info.module ? info.module.name : 'REQUIRED_MODULE_NAME'} by the administrator.
            Should you require any assistance or have any questions regarding the functionality of the LMS, kindly direct your queries at ishfrontdesk@gmail.com.

            Sincerely,
            Admin

            Office of Dr. Omar Minwalla
            The Institute for Sexual Health (ISH)
            www.MinwallaModel.com
          `,
        };
      }
      case ENUM_EMAIL_TEMPLATES.CLIENT_MODULE_UNASSIGNED: {
        return {
          from: `ISH-Admin ${process.env.GMAIL_SMTP_USERNAME}`,
          to: recipient,
          subject: 'Module Unassigned!',
          text: `
            Dear ${info.user.name},

              We regret to inform you that your access to the module named ${info.module ? info.module.name : 'REQUIRED_MODULE_NAME'} has been revoked by the administrator.             If you have any questions or require further information, please direct your queries to ishfrontdesk@gmail.com

            Sincerely,
            Admin

            Office of Dr. Omar Minwalla
            The Institute for Sexual Health (ISH)
            www.MinwallaModel.com
`,
        };
      }
      default:
        throw new Error('Invalid template.');
    }
  }

  public async send(mailOptions: IMailOptions): Promise<any> {
    try {
      if (this.sendEmail) {
        console.log(this);
        const transporter = this.getGmailTransport();
        const sendDetails = await transporter.sendMail(mailOptions);
        console.log('sendDetails', sendDetails);
        // await new Promise((r) => setTimeout(r, 1000));
      }
    } catch (err) {
      // @ts-ignore
      throw new Error(err);
    }
  }
}
const emailManager = new EmailManager();

export { emailManager, ENUM_EMAIL_TEMPLATES };
