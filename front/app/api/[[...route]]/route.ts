import { NextResponse, type NextRequest } from "next/server"

// Set the backend API URL to match your Laravel setup
// CHANGED TO HTTPS
const API_URL = "http://127.0.0.1:8000/api" // Change from https to http

console.log("API proxy configured for:", API_URL)

async function handleRequest(request: NextRequest, { params }: { params: { route: string[] } }, method: string) {
  const route = (await params).route?.join("/") || ""

  // Map frontend routes to Laravel API routes
  let apiRoute = route
  if (route.startsWith("page/")) {
    apiRoute = "home/" + route.substring(5)
  } else if (route.startsWith("search/")) {
    const searchType = route.split("/")[1]?.split("?")[0]
    const queryString = request.url.includes("?") ? request.url.substring(request.url.indexOf("?")) : ""
    apiRoute = `home/find-${searchType}${queryString}`
  } else if (route === "contact/submit") {
    apiRoute = "contact/submit"
  } else if (route === "checkout/session") {
    apiRoute = "checkout/session"
  }

  const url = `${API_URL}/${apiRoute}`

  console.log(`${method}: Frontend route '${route}' -> API route '${apiRoute}' -> ${url}`)

  try {
    const requestBody = method === "POST" ? await request.json() : undefined
    console.log(`${method} body:`, requestBody)

    const response = await fetch(url, {
      method,
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: requestBody ? JSON.stringify(requestBody) : undefined,
      // Add 'no-cors' mode for local development if you encounter CORS issues with HTTPS
      // mode: 'no-cors', // Only use if absolutely necessary and understand implications
    })

    const responseText = await response.text()
    const contentType = response.headers.get("content-type") || ""

    console.log(`Response status:`, response.status)
    console.log(`Response headers:`, Object.fromEntries(response.headers.entries()))
    console.log(`Response body (first 500 chars):`, responseText.substring(0, 500))

    // If response is not OK or not JSON, return a structured error from the proxy
    if (!response.ok || !contentType.includes("application/json")) {
      console.error(
        `Upstream API returned non-OK or non-JSON response. Status: ${response.status}, Content-Type: ${contentType}, Raw: ${responseText.substring(0, 500)}`,
      )
      return NextResponse.json(
        {
          error: "Upstream API error or non-JSON response",
          status: response.status,
          upstreamContentType: contentType,
          upstreamRawResponse: responseText.substring(0, 1000), // Limit size for safety
        },
        { status: response.status >= 400 ? response.status : 500 }, // Use upstream status if it's an error, else 500
      )
    }

    // If it's OK and JSON, parse and return
    let responseData
    try {
      responseData = JSON.parse(responseText)
    } catch (e) {
      // This catch block handles cases where Content-Type is JSON but content is malformed JSON
      console.error(`Failed to parse upstream JSON response:`, e)
      return NextResponse.json(
        {
          error: "Malformed JSON from upstream API",
          details: responseText.substring(0, 1000),
          status: response.status,
        },
        { status: 500 },
      )
    }

    return NextResponse.json(responseData, { status: response.status })
  } catch (error) {
    console.error("API proxy network error:", error)
    return NextResponse.json(
      { error: "Proxy network error", message: error instanceof Error ? error.message : "Unknown error" },
      { status: 500 },
    )
  }
}

export async function GET(request: NextRequest, { params }: { params: { route: string[] } }) {
  return handleRequest(request, { params }, "GET")
}

export async function POST(request: NextRequest, { params }: { params: { route: string[] } }) {
  return handleRequest(request, { params }, "POST")
}

// Handle OPTIONS requests for CORS
export async function OPTIONS(request: NextRequest) {
  return new NextResponse(null, {
    status: 200,
    headers: {
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, OPTIONS",
      "Access-Control-Allow-Headers": "Content-Type, Authorization",
    },
  })
}
