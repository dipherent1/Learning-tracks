// src/app/api/auth/[...nextauth]/route.ts
import NextAuth from "next-auth"
import GitHubProvider from "next-auth/providers/github"
import { PrismaAdapter } from "@auth/prisma-adapter"
import prisma from "@/lib/prisma"


console.log("--- SERVER-SIDE AUTHENTICATION ---");
console.log("GITHUB_ID:", process.env.GITHUB_ID);
console.log("GITHUB_SECRET:", process.env.GITHUB_SECRET);
console.log("---------------------------------");

export const authOptions = {
  adapter: PrismaAdapter(prisma),
  providers: [
    GitHubProvider({
      clientId: process.env.GITHUB_ID!,
      clientSecret: process.env.GITHUB_SECRET!,
    }),
  ],
}

const handler = NextAuth(authOptions)

export { handler as GET, handler as POST }