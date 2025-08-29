import type { NextRequest } from "next/server"

export const maxDuration = 30

export async function POST(req: NextRequest) {
  try {
    console.log("🔍 Chat API called")

    const { messages } = await req.json()
    console.log("📨 Received messages:", messages?.length || 0)

    if (!messages || !Array.isArray(messages)) {
      return new Response("Invalid messages format", { status: 400 })
    }

    if (!process.env.GROQ_API_KEY) {
      return new Response("Missing API key", { status: 500 })
    }

    // Format messages for Groq API
    const formattedMessages = [
      {
        role: "system",
        content: `Tu es un assistant virtuel professionnel d'UIB (Union Internationale de Banques) en Tunisie. 

INFORMATIONS UIB:
- Fondée en 1963 (plus de 60 ans d'expérience)
- Plus de 120 agences à travers la Tunisie
- Plus de 800 000 clients actifs
- Siège social: Avenue Habib Bourguiba, Tunis
- Centre d'appels: +216 71 144 144
- Email: contact@uib.com.tn
- Horaires: Lundi-Vendredi 8h-16h30

SERVICES UIB:
- Comptes courants et d'épargne
- Crédits (immobilier, auto, personnel, entreprise)
- Cartes bancaires (Visa, Mastercard)
- Application mobile UIB
- Virements nationaux et internationaux
- Solutions d'investissement et épargne
- Distributeurs automatiques 24h/24

INSTRUCTIONS:
- Réponds UNIQUEMENT en français
- Sois professionnel, courtois et précis
- Fournis des informations concrètes sur UIB
- Pour les questions complexes, dirige vers le +216 71 144 144
- Ne divulgue JAMAIS d'informations de compte personnelles`,
      },
      ...messages.map((msg: any) => ({
        role: msg.role,
        content: msg.content,
      })),
    ]

    console.log("🤖 Calling Groq API with formatted messages")

    // Use the exact same format that works in your Postman
    const groqPayload = {
      model: "llama3-70b-8192",
      messages: formattedMessages,
      max_tokens: 500,
      temperature: 0.3,
      stream: false, // Start with non-streaming to debug
    }

    console.log("📤 Groq payload:", JSON.stringify(groqPayload, null, 2))

    const groqResponse = await fetch("https://api.groq.com/openai/v1/chat/completions", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${process.env.GROQ_API_KEY}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify(groqPayload),
    })

    console.log("📥 Groq response status:", groqResponse.status)

    if (!groqResponse.ok) {
      const errorText = await groqResponse.text()
      console.error("❌ Groq API error response:", errorText)
      throw new Error(`Groq API error: ${groqResponse.status} - ${errorText}`)
    }

    const groqData = await groqResponse.json()
    console.log("✅ Groq API success:", groqData)

    const assistantMessage = groqData.choices?.[0]?.message?.content || "Désolé, je n'ai pas pu générer une réponse."

    // Return in AI SDK format for useChat compatibility
    return new Response(
      JSON.stringify({
        id: Date.now().toString(),
        role: "assistant",
        content: assistantMessage,
      }),
      {
        headers: {
          "Content-Type": "application/json",
        },
      },
    )
  } catch (error) {
    console.error("❌ Chat API Error:", error)

    return new Response(
      JSON.stringify({
        error: "Service temporairement indisponible",
        message: "Veuillez contacter le +216 71 144 144 pour une assistance immédiate.",
        details: error instanceof Error ? error.message : "Unknown error",
      }),
      {
        status: 500,
        headers: {
          "Content-Type": "application/json",
        },
      },
    )
  }
}

// Test endpoint
export async function GET() {
  try {
    console.log("🔍 Testing Groq connection...")

    // Test with the exact same model and format
    const testPayload = {
      model: "llama3-70b-8192",
      messages: [
        {
          role: "user",
          content: "Hello, test message",
        },
      ],
      max_tokens: 50,
    }

    const testResponse = await fetch("https://api.groq.com/openai/v1/chat/completions", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${process.env.GROQ_API_KEY}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify(testPayload),
    })

    const testData = testResponse.ok ? await testResponse.json() : await testResponse.text()

    return new Response(
      JSON.stringify({
        status: "API route is working",
        hasGroqKey: !!process.env.GROQ_API_KEY,
        keyLength: process.env.GROQ_API_KEY?.length || 0,
        groqConnection: testResponse.ok ? "✅ Connected" : "❌ Failed",
        testResponse: testData,
        timestamp: new Date().toISOString(),
      }),
      {
        headers: {
          "Content-Type": "application/json",
        },
      },
    )
  } catch (error) {
    return new Response(
      JSON.stringify({
        status: "API route error",
        error: error instanceof Error ? error.message : "Unknown error",
        hasGroqKey: !!process.env.GROQ_API_KEY,
        timestamp: new Date().toISOString(),
      }),
      {
        headers: {
          "Content-Type": "application/json",
        },
      },
    )
  }
}
