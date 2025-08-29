"use client"

import { useState, useEffect } from "react"
import type { UseFormReturn } from "react-hook-form"
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { useLanguage } from "@/contexts/language-context"

interface LocationOption {
  id: number
  text: string
}

interface LocationSelectorProps {
  form: UseFormReturn<any>
}

export default function LocationSelector({ form }: LocationSelectorProps) {
  const [countries, setCountries] = useState<LocationOption[]>([])
  const [states, setStates] = useState<LocationOption[]>([])
  const [cities, setCities] = useState<LocationOption[]>([])
  const [loadingCountries, setLoadingCountries] = useState(false)
  const [loadingStates, setLoadingStates] = useState(false)
  const [loadingCities, setLoadingCities] = useState(false)
  const { t } = useLanguage()

  // Fetch countries on component mount
  useEffect(() => {
    const fetchCountries = async () => {
      setLoadingCountries(true)
      try {
        const response = await fetch("/api/search/countries?term=&page=1")
        if (!response.ok) throw new Error("Failed to fetch countries")
        const data = await response.json()
        console.log("Countries data:", data)
        setCountries(data.results || [])
      } catch (error) {
        console.error("Error fetching countries:", error)
      } finally {
        setLoadingCountries(false)
      }
    }

    fetchCountries()
  }, [])

  // Fetch states when country changes
  const handleCountryChange = async (countryId: string) => {
    form.setValue("state", "")
    form.setValue("city", "")
    setStates([])
    setCities([])

    if (!countryId) return

    setLoadingStates(true)
    try {
      const response = await fetch(`/api/search/states?country_id=${countryId}&term=&page=1`)
      if (!response.ok) throw new Error("Failed to fetch states")
      const data = await response.json()
      console.log("States data:", data)
      setStates(data.results || [])
    } catch (error) {
      console.error("Error fetching states:", error)
    } finally {
      setLoadingStates(false)
    }
  }

  // Fetch cities when state changes
  const handleStateChange = async (stateId: string) => {
    form.setValue("city", "")
    setCities([])

    if (!stateId || !form.getValues("country")) return

    setLoadingCities(true)
    try {
      const response = await fetch(
        `/api/search/cities?country_id=${form.getValues("country")}&state_id=${stateId}&term=&page=1`,
      )
      if (!response.ok) throw new Error("Failed to fetch cities")
      const data = await response.json()
      console.log("Cities data:", data)
      setCities(data.results || [])
    } catch (error) {
      console.error("Error fetching cities:", error)
    } finally {
      setLoadingCities(false)
    }
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
      <FormField
        control={form.control}
        name="country"
        render={({ field }) => (
          <FormItem>
            <FormLabel>{t("form.country")}</FormLabel>
            <Select
              disabled={loadingCountries}
              onValueChange={(value) => {
                field.onChange(value)
                handleCountryChange(value)
              }}
              value={field.value?.toString() || ""}
            >
              <FormControl>
                <SelectTrigger>
                  <SelectValue placeholder={t("form.placeholder.selectCountry")} />
                </SelectTrigger>
              </FormControl>
              <SelectContent>
                {loadingCountries ? (
                  <SelectItem value="loading" disabled>
                    {t("form.loading")}
                  </SelectItem>
                ) : (
                  countries.map((country) => (
                    <SelectItem key={country.id} value={country.id.toString()}>
                      {country.text}
                    </SelectItem>
                  ))
                )}
              </SelectContent>
            </Select>
            <FormMessage />
          </FormItem>
        )}
      />

      <FormField
        control={form.control}
        name="state"
        render={({ field }) => (
          <FormItem>
            <FormLabel>{t("form.state")}</FormLabel>
            <Select
              disabled={loadingStates || !form.getValues("country")}
              onValueChange={(value) => {
                field.onChange(value)
                handleStateChange(value)
              }}
              value={field.value?.toString() || ""}
            >
              <FormControl>
                <SelectTrigger>
                  <SelectValue placeholder={t("form.placeholder.selectState")} />
                </SelectTrigger>
              </FormControl>
              <SelectContent>
                {loadingStates ? (
                  <SelectItem value="loading" disabled>
                    {t("form.loading")}
                  </SelectItem>
                ) : (
                  states.map((state) => (
                    <SelectItem key={state.id} value={state.id.toString()}>
                      {state.text}
                    </SelectItem>
                  ))
                )}
              </SelectContent>
            </Select>
            <FormMessage />
          </FormItem>
        )}
      />

      <FormField
        control={form.control}
        name="city"
        render={({ field }) => (
          <FormItem>
            <FormLabel>{t("form.city")}</FormLabel>
            <Select
              disabled={loadingCities || !form.getValues("state")}
              onValueChange={field.onChange}
              value={field.value?.toString() || ""}
            >
              <FormControl>
                <SelectTrigger>
                  <SelectValue placeholder={t("form.placeholder.selectCity")} />
                </SelectTrigger>
              </FormControl>
              <SelectContent>
                {loadingCities ? (
                  <SelectItem value="loading" disabled>
                    {t("form.loading")}
                  </SelectItem>
                ) : (
                  cities.map((city) => (
                    <SelectItem key={city.id} value={city.id.toString()}>
                      {city.text}
                    </SelectItem>
                  ))
                )}
              </SelectContent>
            </Select>
            <FormMessage />
          </FormItem>
        )}
      />
    </div>
  )
}
