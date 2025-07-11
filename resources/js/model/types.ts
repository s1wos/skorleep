interface EmailAttachment {
  filename: string | null
  url: string | null
  mime: string | null
  size: number | null
}

interface EmailMessage {
  from: string
  subject: string
  body_text: string
  received_at: string
  attachments: EmailAttachment[]
}