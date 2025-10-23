// src/components/AuthButton.tsx
'use client'

import { useSession, signIn, signOut } from 'next-auth/react'
import Image from 'next/image'

export default function AuthButton() {
  const { data: session } = useSession()

  if (session) {
    return (
      <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
        {session.user?.image && (
          <Image
            src={session.user.image}
            alt={session.user.name || ''}
            width={32}
            height={32}
            style={{ borderRadius: '50%' }}
          />
        )}
        <p>{session.user?.name}</p>
        <button onClick={() => signOut()}>Sign Out</button>
      </div>
    )
  }

  return (
    <div>
      <p>Not signed in</p>
      <button onClick={() => signIn('github')}>Sign In with GitHub</button>
    </div>
  )
}