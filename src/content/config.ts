import { defineCollection, z } from 'astro:content';

const projectsCollection = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    shortDescription: z.string(),
    category: z.enum(['children', 'education', 'health', 'social', 'other']),
    status: z.enum(['active', 'completed', 'archived']).default('active'),
    targetAmount: z.number().optional(),
    collectedAmount: z.number().default(0),
    beneficiariesCount: z.number().default(0),
    regions: z.array(z.string()).default([]),
    imageUrl: z.string(),
    milestones: z.array(z.object({
      title: z.string(),
      description: z.string().optional(),
      targetDate: z.string().optional(),
      isCompleted: z.boolean().default(false),
      sortOrder: z.number().default(0)
    })).optional(),
    publishedAt: z.date().default(() => new Date()),
  })
});

const newsCollection = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    excerpt: z.string(),
    projectSlug: z.string().optional(),
    imageUrl: z.string().optional(),
    publishedAt: z.date().default(() => new Date()),
  })
});

const reportsCollection = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    year: z.number(),
    type: z.enum(['financial', 'project', 'annual']),
    projectSlug: z.string().optional(),
    fileUrl: z.string(),
    publishedAt: z.date().default(() => new Date()),
  })
});

const partnersCollection = defineCollection({
  type: 'data',
  schema: z.object({
    name: z.string(),
    logoUrl: z.string().optional(),
    description: z.string().optional(),
    website: z.string().optional(),
    sortOrder: z.number().default(0),
    isActive: z.boolean().default(true),
  })
});

export const collections = {
  'projects': projectsCollection,
  'news': newsCollection,
  'reports': reportsCollection,
  'partners': partnersCollection,
};