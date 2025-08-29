"use client"

import { FormDescription } from "@/components/ui/form"

import { useState } from "react"
import { zodResolver } from "@hookform/resolvers/zod"
import { useForm } from "react-hook-form"
import { z } from "zod"
import { Button } from "@/components/ui/button"
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Card, CardContent } from "@/components/ui/card"
import { Loader2, CheckCircle } from "lucide-react"
import { useLanguage } from "@/contexts/language-context"

export default function ContactForm() {
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [isSuccess, setIsSuccess] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const { t } = useLanguage()

  const formSchema = z.object({
    name: z
      .string()
      .min(2, {
        message: t("form.validation.nameMin"),
      })
      .max(50, {
        message: t("form.validation.nameMax"),
      }),
    email: z.string().email({
      message: t("form.validation.emailInvalid"),
    }),
    mobile: z
      .string()
      .regex(/^[6-9][0-9]{9}$/, {
        message: t("form.validation.mobileInvalid"),
      })
      .optional()
      .or(z.literal("")), // Allow empty string for optional
    message: z.string().min(10, {
      message: "Message must be at least 10 characters.", // This specific message is not in translations yet
    }),
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      name: "",
      email: "",
      mobile: "",
      message: "",
    },
  })

  async function onSubmit(values: z.infer<typeof formSchema>) {
    setIsSubmitting(true)
    setError(null)
    setIsSuccess(false)

    try {
      console.log("Submitting contact form:", values)

      const response = await fetch("/api/contact/submit", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(values),
      })

      console.log("Contact form response status:", response.status)

      // Get the response text first for debugging
      const responseText = await response.text()
      console.log("Contact form raw response:", responseText)

      // Try to parse the response as JSON
      let data
      try {
        data = JSON.parse(responseText)
      } catch (e) {
        console.error("Failed to parse response as JSON:", e)
        throw new Error(`${t("form.error.emptyResponse")}: ${responseText.substring(0, 100)}...`)
      }

      if (!response.ok) {
        throw new Error(data.message || t("form.error.checkoutFailed"))
      }

      setIsSuccess(true)
      form.reset()
    } catch (err) {
      console.error("Contact form submission error:", err)
      setError(err instanceof Error ? err.message : t("form.error.unexpected"))
    } finally {
      setIsSubmitting(false)
    }
  }

  return (
    <Card>
      <CardContent className="p-6">
        {error && <div className="bg-red-50 border border-red-200 text-red-600 p-4 rounded-md mb-6">{error}</div>}

        {isSuccess && (
          <div className="bg-green-50 border border-green-200 text-green-600 p-4 rounded-md mb-6 flex items-center gap-2">
            <CheckCircle className="h-5 w-5" />
            <span>{t("form.success.messageSent")}</span>
          </div>
        )}

        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
            <FormField
              control={form.control}
              name="name"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>{t("form.fullName")}</FormLabel>
                  <FormControl>
                    <Input placeholder={t("form.placeholder.yourName")} {...field} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormField
                control={form.control}
                name="email"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>{t("form.email")}</FormLabel>
                    <FormControl>
                      <Input type="email" placeholder={t("form.placeholder.yourEmail")} {...field} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />

              <FormField
                control={form.control}
                name="mobile"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>{t("form.mobile")}</FormLabel>
                    <FormControl>
                      <Input placeholder={t("form.placeholder.mobile")} {...field} />
                    </FormControl>
                    <FormDescription>{t("form.mobileDescription")}</FormDescription>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </div>

            <FormField
              control={form.control}
              name="message"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Message</FormLabel> {/* This label is not in translations yet */}
                  <FormControl>
                    <Textarea placeholder={t("form.placeholder.message")} className="min-h-[120px]" {...field} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <Button type="submit" className="w-full" disabled={isSubmitting}>
              {isSubmitting ? (
                <>
                  <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                  {t("form.sending")}
                </>
              ) : (
                t("form.sendMessage")
              )}
            </Button>
          </form>
        </Form>
      </CardContent>
    </Card>
  )
}
