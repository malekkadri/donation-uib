"use client"

import type React from "react"
import { useState, useEffect } from "react"
import axios from "axios"

const LaravelTest: React.FC = () => {
  const [webTestResult, setWebTestResult] = useState<string | null>(null)
  const [contactResult, setContactResult] = useState<string | null>(null)
  const [proxyContactResult, setProxyContactResult] = useState<string | null>(null)

  const webTestUrl = "http://127.0.0.1:8000/api/test" // Changed to a known working API endpoint
  const contactApiUrl = "http://127.0.0.1:8000/api/contact/submit"
  const contactProxyUrl = "/api/contact/submit"

  useEffect(() => {
    const testWebEndpoint = async () => {
      try {
        const response = await axios.get(webTestUrl)
        setWebTestResult(response.data.message)
      } catch (error: any) {
        setWebTestResult(`Error: ${error.message}`)
      }
    }

    testWebEndpoint()
  }, [webTestUrl])

  const testContactEndpoint = async () => {
    try {
      const response = await axios.post(contactApiUrl, {
        name: "Test User",
        email: "test@example.com",
        message: "This is a test message.",
      })
      setContactResult(response.data.message)
    } catch (error: any) {
      setContactResult(`Error: ${error.message}`)
    }
  }

  const testProxyContactEndpoint = async () => {
    try {
      const response = await axios.post(contactProxyUrl, {
        name: "Test User",
        email: "test@example.com",
        message: "This is a test message.",
      })
      setProxyContactResult(response.data.message)
    } catch (error: any) {
      setProxyContactResult(`Error: ${error.message}`)
    }
  }

  return (
    <div>
      <h2>Laravel API Tests</h2>

      <h3>Web Test</h3>
      <p>URL: {webTestUrl}</p>
      <button onClick={() => window.location.reload()}>Refresh Web Test</button>
      {webTestResult && <p>Result: {webTestResult}</p>}

      <h3>Direct Contact API Test</h3>
      <p>URL: {contactApiUrl}</p>
      <button onClick={testContactEndpoint}>Test Contact API</button>
      {contactResult && <p>Result: {contactResult}</p>}

      <h3>Proxy Contact API Test</h3>
      <p>URL: {contactProxyUrl}</p>
      <button onClick={testProxyContactEndpoint}>Test Proxy Contact API</button>
      {proxyContactResult && <p>Result: {proxyContactResult}</p>}
    </div>
  )
}

export default LaravelTest
