"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"

export default function LaravelDebug() {
  const [results, setResults] = useState<any[]>([])
  const [loading, setLoading] = useState(false)

  const runDiagnostics = async () => {
    setLoading(true)
    setResults([])

    const tests = [
      {
        name: "Laravel Web Test",
        url: "http://127.0.0.1:8000/test-laravel",
        method: "GET",
        description: "Test if Laravel is running",
      },
      {
        name: "Laravel API Test",
        url: "http://127.0.0.1:8000/api/test",
        method: "GET",
        description: "Test if API routes are working",
      },
      {
        name: "Contact API Direct",
        url: "http://127.0.0.1:8000/api/contact/submit",
        method: "POST",
        body: {
          name: "Test User",
          email: "test@example.com",
          mobile: "1234567890",
          message: "Test message",
        },
        description: "Test contact form directly",
      },
      {
        name: "Contact API via Proxy",
        url: "/api/contact/submit",
        method: "POST",
        body: {
          name: "Test User",
          email: "test@example.com",
          mobile: "1234567890",
          message: "Test message",
        },
        description: "Test contact form via Next.js proxy",
      },
    ]

    for (const test of tests) {
      try {
        console.log(`Running test: ${test.name}`)

        const options: RequestInit = {
          method: test.method,
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          mode: test.url.startsWith("http") ? "cors" : "same-origin",
        }

        if (test.method === "POST" && test.body) {
          options.body = JSON.stringify(test.body)
        }

        const startTime = Date.now()
        const response = await fetch(test.url, options)
        const endTime = Date.now()

        let responseData
        let rawResponse = ""

        try {
          rawResponse = await response.text()
          responseData = JSON.parse(rawResponse)
        } catch (e) {
          responseData = {
            error: "Failed to parse JSON",
            rawResponse: rawResponse.substring(0, 500),
          }
        }

        setResults((prev) => [
          ...prev,
          {
            ...test,
            status: response.status,
            statusText: response.statusText,
            success: response.ok,
            responseTime: endTime - startTime,
            headers: Object.fromEntries(response.headers.entries()),
            data: responseData,
            rawResponse: rawResponse,
            error: null,
          },
        ])
      } catch (error) {
        console.error(`Test ${test.name} failed:`, error)
        setResults((prev) => [
          ...prev,
          {
            ...test,
            status: "Network Error",
            statusText: "Failed to connect",
            success: false,
            responseTime: 0,
            headers: {},
            data: null,
            rawResponse: "",
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
        <CardTitle>Laravel API Diagnostics</CardTitle>
        <Button onClick={runDiagnostics} disabled={loading}>
          {loading ? "Running Tests..." : "Run Diagnostics"}
        </Button>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          {results.length === 0 && !loading && (
            <p className="text-center text-muted-foreground py-8">
              Click "Run Diagnostics" to test your Laravel API connection
            </p>
          )}

          {loading && <p className="text-center text-muted-foreground py-8">Running diagnostics, please wait...</p>}

          {results.map((result, index) => (
            <div
              key={index}
              className={`p-4 rounded-lg border ${
                result.success ? "border-green-200 bg-green-50" : "border-red-200 bg-red-50"
              }`}
            >
              <div className="flex justify-between items-start mb-3">
                <div>
                  <h3 className="font-semibold text-lg">{result.name}</h3>
                  <p className="text-sm text-gray-600">{result.description}</p>
                  <p className="text-xs text-gray-500 mt-1">
                    {result.method} {result.url}
                  </p>
                </div>
                <div className="flex gap-2">
                  <Badge variant={result.success ? "default" : "destructive"}>
                    {result.status} {result.statusText}
                  </Badge>
                  {result.responseTime > 0 && <Badge variant="outline">{result.responseTime}ms</Badge>}
                </div>
              </div>

              {result.error && (
                <div className="mb-3 p-2 bg-red-100 border border-red-200 rounded text-red-700 text-sm">
                  <strong>Error:</strong> {result.error}
                </div>
              )}

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                {result.rawResponse && (
                  <div>
                    <h4 className="font-medium mb-1">Raw Response:</h4>
                    <pre className="p-2 bg-gray-100 rounded overflow-auto max-h-32 text-xs">
                      {result.rawResponse.substring(0, 1000)}
                      {result.rawResponse.length > 1000 ? "..." : ""}
                    </pre>
                  </div>
                )}

                {result.data && (
                  <div>
                    <h4 className="font-medium mb-1">Parsed Data:</h4>
                    <pre className="p-2 bg-gray-100 rounded overflow-auto max-h-32 text-xs">
                      {JSON.stringify(result.data, null, 2)}
                    </pre>
                  </div>
                )}
              </div>

              {Object.keys(result.headers).length > 0 && (
                <details className="mt-3">
                  <summary className="cursor-pointer text-sm font-medium">Response Headers</summary>
                  <pre className="mt-1 p-2 bg-gray-100 rounded text-xs overflow-auto">
                    {JSON.stringify(result.headers, null, 2)}
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
