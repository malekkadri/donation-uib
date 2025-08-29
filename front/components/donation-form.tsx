"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { zodResolver } from "@hookform/resolvers/zod"
import { useForm } from "react-hook-form"
import { z } from "zod"
import { Button } from "@/components/ui/button"
import { Form, FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Checkbox } from "@/components/ui/checkbox"
import { Card, CardContent } from "@/components/ui/card"
import { Loader2, XCircle } from "lucide-react"
import LocationSelector from "@/components/location-selector"
import { motion, AnimatePresence } from "framer-motion"
import { useLanguage } from "@/contexts/language-context"

export default function DonationForm() {
  const router = useRouter()
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [isSuccess, setIsSuccess] = useState(false)
  const [debugInfo, setDebugInfo] = useState<string | null>(null)
  const { t } = useLanguage()

  const formSchema = z.object({
    name: z
      .string()
      .min(2, {
        message: t("form.validation.nameMin"),
      })
      .max(100, {
        message: t("form.validation.nameMax"),
      }),
    email: z.string().email({
      message: t("form.validation.emailInvalid"),
    }),
    mobile: z.string().optional(),
    amount: z.coerce.number().min(1, {
      // Minimum amount will be enforced by Laravel's env
      message: t("form.validation.amountMin"),
    }),
    country: z.coerce.number({
      required_error: t("form.validation.countryRequired"),
    }),
    state: z.coerce.number({
      required_error: t("form.validation.stateRequired"),
    }),
    city: z.coerce.number({
      required_error: t("form.validation.cityRequired"),
    }),
    street_address: z.string().optional(),
    add_to_leaderboard: z.boolean().default(false),
    cause: z.string().min(1, { message: t("form.validation.causeRequired") }),

  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      name: "",
      email: "",
      mobile: "",
      amount: 100,
      street_address: "",
      add_to_leaderboard: true,
      cause: "",
    },
  })

  async function onSubmit(values: z.infer<typeof formSchema>) {
    setIsSubmitting(true)
    setError(null)
    setDebugInfo(null)
    setIsSuccess(false)

    try {
      console.log("Submitting donation form:", values)

      const formData = {
        ...values,
        add_to_leaderboard: values.add_to_leaderboard ? "yes" : "no",
      }

      console.log("Formatted form data:", formData)

      const response = await fetch("/api/checkout/session", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })

      console.log("Response status:", response.status)

      const responseText = await response.text()
      console.log("Raw response:", responseText)
      setDebugInfo(responseText)

      let data: any
      try {
        data = JSON.parse(responseText)
      } catch (e) {
        console.error("Failed to parse response as JSON:", e)
        setError(`${t("form.error.emptyResponse")} Raw: ${responseText.substring(0, 200)}...`)
        return
      }

      if (!response.ok) {
        console.error("Checkout error response:", data)
        let errorMessage = `${t("form.error.checkoutFailed")} ${response.status} ${response.statusText}`

        if (data && data.message) {
          errorMessage = data.message
        } else if (data && data.errors) {
          const validationErrors = Object.values(data.errors).flat().join(", ")
          errorMessage = `${t("form.error.validationFailed")} ${validationErrors}`
        } else if (Object.keys(data).length === 0) {
          errorMessage = `${t("form.error.emptyResponse")}`
        }

        setError(errorMessage)
        return
      }

      console.log("Checkout response:", data)

      // Redirect to Stripe checkout URL
      if (data.url) {
        window.location.href = data.url
      } else {
        throw new Error(t("form.error.noUrl"))
      }
    } catch (err) {
      console.error("Donation submission error:", err)
      setError(err instanceof Error ? err.message : t("form.error.unexpected"))
    } finally {
      setIsSubmitting(false)
    }
  }

  const fieldVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: "easeOut" } },
  }

  const messageVariants = {
    hidden: { opacity: 0, y: -20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: "easeOut" } },
    exit: { opacity: 0, y: 20, transition: { duration: 0.3, ease: "easeIn" } },
  }

  return (
    <Card className="bg-card border-border">
      <CardContent className="p-6">
        <AnimatePresence>
          {error && (
            <motion.div
              className="bg-red-50 border border-red-200 text-red-600 p-4 rounded-md mb-6 flex items-center gap-2"
              variants={messageVariants}
              initial="hidden"
              animate="visible"
              exit="exit"
            >
              <XCircle className="h-5 w-5" />
              <span>{error}</span>
            </motion.div>
          )}
        </AnimatePresence>

        {debugInfo && (
          <div className="bg-gray-50 border border-gray-200 p-4 rounded-md mb-6 text-xs overflow-auto max-h-40 text-foreground">
            <strong>Debug Info:</strong>
            <pre>{debugInfo}</pre>
          </div>
        )}

        {/* Removed isSuccess message as redirection will happen */}

        <Form {...form}>
          <motion.form
            onSubmit={form.handleSubmit(onSubmit)}
            className="space-y-6"
            initial="hidden"
            animate="visible"
            variants={{
              visible: {
                transition: {
                  staggerChildren: 0.1,
                },
              },
            }}
          >
            <motion.div variants={fieldVariants}>
              <FormField
                control={form.control}
                name="name"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>{t("form.fullName")}</FormLabel>
                    <FormControl>
                      <Input
                        placeholder={t("form.placeholder.yourName")}
                        {...field}
                        className="bg-input text-foreground"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </motion.div>

            <motion.div className="grid grid-cols-1 md:grid-cols-2 gap-6" variants={fieldVariants}>
              <FormField
                control={form.control}
                name="email"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>{t("form.email")}</FormLabel>
                    <FormControl>
                      <Input
                        type="email"
                        placeholder={t("form.placeholder.yourEmail")}
                        {...field}
                        className="bg-input text-foreground"
                      />
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
                      <Input
                        placeholder={t("form.placeholder.mobile")}
                        {...field}
                        className="bg-input text-foreground"
                      />
                    </FormControl>
                    <FormDescription>{t("form.mobileDescription")}</FormDescription>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </motion.div>
              <motion.div variants={fieldVariants}>
                  <FormField
                    control={form.control}
                    name="cause"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>{t("cause")}</FormLabel>
                        <FormControl>
                          <select
                            {...field}
                            className="w-full border rounded-md p-2 bg-input text-foreground"
                          >
                            <option value="">{t("form.placeholder.selectCause")}</option>
                            <option value="poverty">Poverty</option>
                            <option value="war">War</option>
                            <option value="education">Education</option>
                            <option value="healthcare">Healthcare</option>
                            <option value="environment">Environment</option>
                            <option value="human_rights">Human Rights</option>
                            <option value="animal_welfare">Animal Welfare</option>
                            <option value="disaster_relief">Disaster Relief</option>
                          </select>
                        </FormControl>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                </motion.div>

            <motion.div variants={fieldVariants}>
              <FormField
                control={form.control}
                name="amount"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>{t("form.amount")}</FormLabel>
                    <FormControl>
                      <Input type="number" min="1" {...field} className="bg-input text-foreground" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </motion.div>

            <motion.div variants={fieldVariants}>
              <LocationSelector form={form} />
            </motion.div>

            <motion.div variants={fieldVariants}>
              <FormField
                control={form.control}
                name="street_address"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>{t("form.streetAddress")}</FormLabel>
                    <FormControl>
                      <Textarea
                        placeholder={t("form.placeholder.streetAddress")}
                        {...field}
                        className="min-h-[120px] bg-input text-foreground"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </motion.div>

            <motion.div variants={fieldVariants}>
              <FormField
                control={form.control}
                name="add_to_leaderboard"
                render={({ field }) => (
                  <FormItem className="flex flex-row items-start space-x-3 space-y-0">
                    <FormControl>
                      <Checkbox checked={field.value} onCheckedChange={field.onChange} />
                    </FormControl>
                    <div className="space-y-1 leading-none">
                      <FormLabel>{t("form.addToLeaderboard")}</FormLabel>
                      <FormDescription>{t("form.addToLeaderboardDescription")}</FormDescription>
                    </div>
                  </FormItem>
                )}
              />
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: 0.8, ease: "easeOut" }}
            >
              <Button type="submit" className="w-full" disabled={isSubmitting}>
                {isSubmitting ? (
                  <>
                    <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                    {t("form.processing")}
                  </>
                ) : (
                  t("form.proceedToPayment")
                )}
              </Button>
            </motion.div>
          </motion.form>
        </Form>
      </CardContent>
    </Card>
  )
}
