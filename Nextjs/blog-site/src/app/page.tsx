// src/app/page.tsx
import Link from 'next/link'
import AuthButton from '@/components/AuthButton'
import { getSortedPostsData } from '@/lib/posts'

// This is now an async component!
export default async function Home() {
  // 1. Fetch post data on the server
  const allPostsData = getSortedPostsData()

  return (
    <main style={{ maxWidth: '800px', margin: '0 auto', padding: '2rem' }}>
      <header style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <h1>My Professional Blog</h1>
        <AuthButton />
      </header>

      <section>
        <h2>Blog Posts</h2>
        <ul>
          {/* 2. Map over the data and render a list of posts */}
          {allPostsData.map(({ id, date, title }) => (
            <li key={id} style={{ marginBottom: '1rem' }}>
              <Link href={`/posts/${id}`}>
                <h3 style={{ fontSize: '1.5rem', marginBottom: '0.5rem' }}>{title}</h3>
              </Link>
              <small style={{ color: '#666' }}>{date}</small>
            </li>
          ))}
        </ul>
      </section>
    </main>
  )
}