"use client"

import type React from "react"

import { useState, useRef, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { MessageCircle, Send, X, Bot, User, Minimize2, Maximize2, RefreshCw } from "lucide-react"

interface Message {
  id: string
  role: "user" | "assistant"
  content: string
  timestamp: Date
}

export function UIBChat() {
  const [isOpen, setIsOpen] = useState(false)
  const [isMinimized, setIsMinimized] = useState(false)
  const [messages, setMessages] = useState<Message[]>([
    {
      id: "welcome",
      role: "assistant",
      content:
        "Bonjour ! Je suis votre assistant virtuel UIB alimenté par l'intelligence artificielle. Comment puis-je vous aider aujourd'hui ? Je peux répondre à vos questions sur nos services bancaires, nos horaires, nos agences, et bien plus encore.",
      timestamp: new Date(),
    },
  ])
  const [input, setInput] = useState("")
  const [isLoading, setIsLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const messagesEndRef = useRef<HTMLDivElement>(null)

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" })
  }

  useEffect(() => {
    scrollToBottom()
  }, [messages])

  const quickQuestions = [
    "Quels sont vos horaires d'ouverture ?",
    "Comment ouvrir un compte UIB ?",
    "Où trouver une agence UIB près de moi ?",
    "Quels sont vos taux de crédit immobilier ?",
    "Comment télécharger l'application mobile UIB ?",
    "Quels documents pour ouvrir un compte ?",
    "Services de carte bancaire UIB",
    "Comment faire un virement international ?",
  ]

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!input.trim() || isLoading) return

    const userMessage: Message = {
      id: Date.now().toString(),
      role: "user",
      content: input.trim(),
      timestamp: new Date(),
    }

    setMessages((prev) => [...prev, userMessage])
    setInput("")
    setIsLoading(true)
    setError(null)

    try {
      const response = await fetch("/api/chat", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          messages: [...messages, userMessage].map((msg) => ({
            role: msg.role,
            content: msg.content,
          })),
        }),
      })

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.details || "Erreur de connexion")
      }

      const data = await response.json()
      const assistantMessage: Message = {
        id: data.id || (Date.now() + 1).toString(),
        role: "assistant",
        content: data.content || "Désolé, je n'ai pas pu générer une réponse.",
        timestamp: new Date(),
      }

      setMessages((prev) => [...prev, assistantMessage])
    } catch (error) {
      console.error("Chat error:", error)
      setError(error instanceof Error ? error.message : "Erreur inconnue")

      // Add error message to chat
      const errorMessage: Message = {
        id: (Date.now() + 1).toString(),
        role: "assistant",
        content:
          "Désolé, je rencontre des difficultés techniques. Veuillez contacter notre centre d'appels au +216 71 144 144 pour une assistance immédiate.",
        timestamp: new Date(),
      }
      setMessages((prev) => [...prev, errorMessage])
    } finally {
      setIsLoading(false)
    }
  }

  const handleQuickQuestion = (question: string) => {
    setInput(question)
    setTimeout(() => {
      const form = document.getElementById("chat-form") as HTMLFormElement
      if (form) {
        form.requestSubmit()
      }
    }, 100)
  }

  const retry = () => {
    setError(null)
    if (input.trim()) {
      handleSubmit({ preventDefault: () => {} } as React.FormEvent)
    }
  }

  if (!isOpen) {
    return (
      <div className="fixed bottom-6 right-6 z-50">
        <Button
          onClick={() => setIsOpen(true)}
          className="h-14 w-14 rounded-full bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110"
        >
          <MessageCircle className="h-6 w-6 text-white" />
        </Button>
        <div className="absolute -top-1 -right-1 h-4 w-4 bg-green-500 rounded-full animate-pulse"></div>
      </div>
    )
  }

  return (
    <div className="fixed bottom-6 right-6 z-50">
      <Card className={`w-96 shadow-2xl transition-all duration-300 ${isMinimized ? "h-16" : "h-[600px]"}`}>
        <CardHeader className="bg-gradient-to-r from-red-600 to-red-700 text-white p-4 rounded-t-lg">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <div className="relative">
                <Bot className="h-5 w-5" />
                <div
                  className={`absolute -bottom-1 -right-1 h-2 w-2 rounded-full ${error ? "bg-red-400" : "bg-green-400"}`}
                ></div>
              </div>
              <div>
                <CardTitle className="text-lg">Assistant UIB</CardTitle>
                <p className="text-xs opacity-90">Alimenté par Groq AI</p>
              </div>
            </div>
            <div className="flex items-center gap-2">
              {error && (
                <Button
                  variant="ghost"
                  size="sm"
                  onClick={retry}
                  className="text-white hover:bg-red-700 h-8 w-8 p-0"
                  title="Réessayer"
                >
                  <RefreshCw className="h-4 w-4" />
                </Button>
              )}
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setIsMinimized(!isMinimized)}
                className="text-white hover:bg-red-700 h-8 w-8 p-0"
              >
                {isMinimized ? <Maximize2 className="h-4 w-4" /> : <Minimize2 className="h-4 w-4" />}
              </Button>
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setIsOpen(false)}
                className="text-white hover:bg-red-700 h-8 w-8 p-0"
              >
                <X className="h-4 w-4" />
              </Button>
            </div>
          </div>
        </CardHeader>

        {!isMinimized && (
          <CardContent className="p-0 flex flex-col h-[536px]">
            {/* Messages Area */}
            <div className="flex-1 overflow-y-auto p-4 space-y-4">
              {messages.map((message) => (
                <div key={message.id} className={`flex ${message.role === "user" ? "justify-end" : "justify-start"}`}>
                  <div
                    className={`max-w-[85%] p-3 rounded-lg ${
                      message.role === "user"
                        ? "bg-red-600 text-white rounded-br-sm"
                        : "bg-gray-100 text-gray-900 rounded-bl-sm shadow-sm"
                    }`}
                  >
                    <div className="flex items-start gap-2">
                      {message.role === "assistant" && (
                        <div className="flex-shrink-0 mt-0.5">
                          <Bot className="h-4 w-4 text-red-600" />
                        </div>
                      )}
                      {message.role === "user" && (
                        <div className="flex-shrink-0 mt-0.5">
                          <User className="h-4 w-4" />
                        </div>
                      )}
                      <div className="text-sm whitespace-pre-wrap leading-relaxed">{message.content}</div>
                    </div>
                  </div>
                </div>
              ))}

              {/* Loading Indicator */}
              {isLoading && (
                <div className="flex justify-start">
                  <div className="bg-gray-100 text-gray-900 p-3 rounded-lg max-w-[85%] rounded-bl-sm shadow-sm">
                    <div className="flex items-center gap-2">
                      <Bot className="h-4 w-4 text-red-600" />
                      <div className="flex space-x-1">
                        <div className="w-2 h-2 bg-red-400 rounded-full animate-bounce"></div>
                        <div
                          className="w-2 h-2 bg-red-400 rounded-full animate-bounce"
                          style={{ animationDelay: "0.1s" }}
                        ></div>
                        <div
                          className="w-2 h-2 bg-red-400 rounded-full animate-bounce"
                          style={{ animationDelay: "0.2s" }}
                        ></div>
                      </div>
                      <span className="text-xs text-gray-500">L'IA réfléchit...</span>
                    </div>
                  </div>
                </div>
              )}
              <div ref={messagesEndRef} />
            </div>

            {/* Quick Questions */}
            {messages.length === 1 && (
              <div className="p-4 border-t bg-gray-50">
                <p className="text-sm text-gray-600 mb-3 font-medium">Questions fréquentes :</p>
                <div className="grid grid-cols-1 gap-2">
                  {quickQuestions.slice(0, 4).map((question, index) => (
                    <Button
                      key={index}
                      variant="ghost"
                      size="sm"
                      onClick={() => handleQuickQuestion(question)}
                      className="w-full justify-start text-left h-auto p-2 text-xs hover:bg-red-50 hover:text-red-600 border border-gray-200 hover:border-red-200 transition-colors"
                    >
                      {question}
                    </Button>
                  ))}
                </div>
              </div>
            )}

            {/* Input Area */}
            <div className="p-4 border-t bg-white">
              <form id="chat-form" onSubmit={handleSubmit} className="flex gap-2">
                <Input
                  value={input}
                  onChange={(e) => setInput(e.target.value)}
                  placeholder="Posez votre question à l'assistant IA UIB..."
                  disabled={isLoading}
                  className="flex-1 border-gray-300 focus:border-red-500 focus:ring-red-500"
                />
                <Button
                  type="submit"
                  disabled={isLoading || !input.trim()}
                  className="bg-red-600 hover:bg-red-700 disabled:opacity-50"
                >
                  <Send className="h-4 w-4" />
                </Button>
              </form>
              <div className="flex items-center justify-between mt-2">
                <p className="text-xs text-gray-500">Propulsé par Groq AI • UIB Assistant</p>
                <div className="flex items-center gap-1">
                  <div className={`w-2 h-2 rounded-full ${error ? "bg-red-400" : "bg-green-400"}`}></div>
                  <span className="text-xs text-gray-500">{error ? "Erreur" : "En ligne"}</span>
                </div>
              </div>
            </div>
          </CardContent>
        )}
      </Card>
    </div>
  )
}
