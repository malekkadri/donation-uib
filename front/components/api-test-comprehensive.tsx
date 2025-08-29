"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"

export default function ApiTestComprehensive() {
  const [testResults, setTestResults] = useState<any[]>([])
  const [loading, setLoading] = useState(false)

  const testEndpoints = [
    { name: "API Test", method: "GET", url: "/api/test" },
    { name: "Donate Page", method: "GET", url: "/api/page/donate" },
    { name: "About Page", method: "GET", url: "/api/page/about" },
    { name: "Albums", method: "GET", url: "/api/page/albums" },
    { name: "Leaderboard", method: "GET", url: "/api/page/leaderboard" },
    { name: "Countries", method: "GET", url: "/api/search/countries?term=&page=1" },
    {
      name: "Contact Submit",
      method: "POST",
      url: "/api/contact/submit",
      body: {
        name: "Test User",
        email: "test@example.com",
        mobile: "1234567890",
        message: "This is a test message",
      },
    },
    {
      name: "Checkout Session",
      method: "POST",
      url: "/api/checkout/session",
      body: {
        first_name: "John",
        last_name: "Doe",
        email: "john@example.com",
        amount: 100,
        country: 1,
        state: 1,
        city: 1,
      },
    },
  ]

  const runTests = async () => {
    setLoading(true)
    setTestResults([])

    for (const endpoint of testEndpoints) {
      try {
        console.log(`Testing: ${endpoint.method} ${endpoint.url}`)

        const options: RequestInit = {
          method: endpoint.method,
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
        }

        if (endpoint.method === "POST" && endpoint.body) {
          options.body = JSON.stringify(endpoint.body)
        }

        const response = await fetch(endpoint.url, options)

        // Get raw response
        const responseText = await response.text()

        let data
        try {
          data = JSON.parse(responseText)
        } catch (e) {
          data = { error: "Failed to parse JSON response", rawResponse: responseText }
        }

        setTestResults((prev) => [
          ...prev,
          {
            name: endpoint.name,
            method: endpoint.method,
            url: endpoint.url,
            status: response.status,
            success: response.ok,
            data: data,
            rawResponse: responseText,
            error: null,
          },
        ])
      } catch (error) {
        setTestResults((prev) => [
          ...prev,
          {
            name: endpoint.name,
            method: endpoint.method,
            url: endpoint.url,
            status: "Error",
            success: false,
            data: null,
            rawResponse: null,
            error: error instanceof Error ? error.message : "Unknown error",
          },
        ])
      }
    }

    setLoading(false)
  }

  return (
    <Card className="w-full max-w-6xl mx-auto">
      <CardHeader className="flex flex-row items-center justify-between">
        <CardTitle>Comprehensive API Test</CardTitle>
        <Button onClick={runTests} disabled={loading}>
          {loading ? "Testing..." : "Run All Tests"}
        </Button>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          {testResults.length === 0 && !loading && (
            <p className="text-center text-muted-foreground py-4">Click the button above to test all API endpoints</p>
          )}

          {loading && <p className="text-center text-muted-foreground py-4">Testing endpoints, please wait...</p>}

          {testResults.map((result, index) => (
            <div
              key={index}
              className={`p-4 rounded border ${
                result.success ? "border-green-200 bg-green-50" : "border-red-200 bg-red-50"
              }`}
            >
              <div className="flex justify-between items-center mb-2">
                <h3 className="font-semibold">
                  {result.name} ({result.method})
                </h3>
                <span
                  className={`px-2 py-1 rounded text-sm ${
                    result.success ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"
                  }`}
                >
                  {result.status}
                </span>
              </div>
              <p className="text-sm text-gray-600 mb-2">{result.url}</p>
              {result.error && <p className="text-red-600 text-sm mb-2">Error: {result.error}</p>}

              {result.rawResponse && (
                <details className="text-xs mb-2">
                  <summary className="cursor-pointer">Raw Response</summary>
                  <pre className="mt-2 p-2 bg-gray-100 rounded overflow-auto max-h-40">
                    {result.rawResponse.substring(0, 1000)}
                    {result.rawResponse.length > 1000 ? "..." : ""}
                  </pre>
                </details>
              )}

              {result.data && (
                <details className="text-xs">
                  <summary className="cursor-pointer">Parsed Data</summary>
                  <pre className="mt-2 p-2 bg-gray-100 rounded overflow-auto max-h-60">
                    {JSON.stringify(result.data, null, 2)}
                  </pre>
                </details>
              )}
            </div>
          ))}
        </div>
      </CardContent>
    </Card>
  )
}
