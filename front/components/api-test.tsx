"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"

export default function ApiTest() {
  const [url, setUrl] = useState("/api/page/donate")
  const [method, setMethod] = useState<"GET" | "POST">("GET")
  const [body, setBody] = useState("")
  const [response, setResponse] = useState<any>(null)
  const [error, setError] = useState<string | null>(null)
  const [loading, setLoading] = useState(false)
  const [rawResponse, setRawResponse] = useState<string | null>(null)

  const testApi = async () => {
    setLoading(true)
    setError(null)
    setResponse(null)
    setRawResponse(null)

    try {
      console.log(`Testing API: ${method} ${url}`)

      const options: RequestInit = {
        method,
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      }

      if (method === "POST" && body) {
        try {
          options.body = JSON.stringify(JSON.parse(body))
        } catch (e) {
          setError(`Invalid JSON in request body: ${e instanceof Error ? e.message : String(e)}`)
          setLoading(false)
          return
        }
      }

      const res = await fetch(url, options)

      // Get raw response
      const clone = res.clone()
      const rawText = await clone.text()
      setRawResponse(rawText)

      // Try to parse as JSON
      try {
        const data = JSON.parse(rawText)
        setResponse(data)
      } catch (e) {
        setError(`Failed to parse response as JSON: ${e instanceof Error ? e.message : String(e)}`)
      }
    } catch (e) {
      setError(`Request failed: ${e instanceof Error ? e.message : String(e)}`)
    } finally {
      setLoading(false)
    }
  }

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle>API Debug Tool</CardTitle>
      </CardHeader>
      <CardContent>
        <Tabs defaultValue="get" className="mb-4">
          <TabsList>
            <TabsTrigger value="get" onClick={() => setMethod("GET")}>
              GET
            </TabsTrigger>
            <TabsTrigger value="post" onClick={() => setMethod("POST")}>
              POST
            </TabsTrigger>
          </TabsList>
          <TabsContent value="get">
            <div className="flex gap-2 mb-4">
              <Input value={url} onChange={(e) => setUrl(e.target.value)} placeholder="API URL" className="flex-1" />
              <Button onClick={testApi} disabled={loading}>
                {loading ? "Testing..." : "Test GET"}
              </Button>
            </div>
          </TabsContent>
          <TabsContent value="post">
            <div className="space-y-4">
              <div className="flex gap-2">
                <Input value={url} onChange={(e) => setUrl(e.target.value)} placeholder="API URL" className="flex-1" />
              </div>
              <div>
                <textarea
                  value={body}
                  onChange={(e) => setBody(e.target.value)}
                  placeholder="Request body (JSON)"
                  className="w-full h-32 p-2 border rounded-md font-mono text-sm"
                />
              </div>
              <Button onClick={testApi} disabled={loading} className="w-full">
                {loading ? "Testing..." : "Test POST"}
              </Button>
            </div>
          </TabsContent>
        </Tabs>

        {error && <div className="p-4 mb-4 bg-red-50 border border-red-200 rounded-md text-red-600">{error}</div>}

        {rawResponse && (
          <div className="mb-4">
            <h3 className="font-medium mb-2">Raw Response:</h3>
            <pre className="p-3 bg-gray-100 rounded-md overflow-auto max-h-40 text-xs">
              {rawResponse.substring(0, 2000)}
              {rawResponse.length > 2000 ? "..." : ""}
            </pre>
          </div>
        )}

        {response && (
          <div>
            <h3 className="font-medium mb-2">Parsed JSON:</h3>
            <pre className="p-3 bg-gray-100 rounded-md overflow-auto max-h-60 text-xs">
              {JSON.stringify(response, null, 2)}
            </pre>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
